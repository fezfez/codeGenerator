<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Config;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\SimpleQuestion;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\DriverHydrator;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\MetaDataSourceCollection;
use CrudGenerator\Metadata\MetaDataSourceHydrator;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\Installer;
use CrudGenerator\Utils\Transtyper;
use KeepUpdate\ArrayValidator;
use KeepUpdate\ValidationException;

class MetaDataConfigDAO
{
    /**
     * @var string Config path
     */
    const SOURCE_PATH = 'Config/';
    /**
     * @var string config file extension
     */
    const EXTENSION = '.source';

    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var Transtyper
     */
    private $transtyper = null;
    /**
     * @var ArrayValidator source validator
     */
    private $arrayValidator = null;
    /**
     * @var MetaDataSourceHydrator MetaData Source Hydrator
     */
    private $metaDataSourceHydrator = null;
    /**
     * @var DriverHydrator
     */
    private $driverHydrator = null;
    /**
     * @var ContextInterface context
     */
    private $context = null;

    /**
     * @param FileManager            $fileManager
     * @param Transtyper             $transtyper
     * @param ArrayValidator         $arrayValidator
     * @param MetaDataSourceHydrator $metaDataSourceHydrator
     * @param DriverHydrator         $driverHydrator
     * @param ContextInterface       $context
     */
    public function __construct(
        FileManager $fileManager,
        Transtyper $transtyper,
        ArrayValidator $arrayValidator,
        MetaDataSourceHydrator $metaDataSourceHydrator,
        DriverHydrator $driverHydrator,
        ContextInterface $context
    ) {
        $this->fileManager             = $fileManager;
        $this->transtyper              = $transtyper;
        $this->arrayValidator          = $arrayValidator;
        $this->metaDataSourceHydrator  = $metaDataSourceHydrator;
        $this->driverHydrator          = $driverHydrator;
        $this->context                 = $context;
    }

    /**
     * Retrieve all config
     *
     * @return \CrudGenerator\Metadata\MetaDataSourceCollection
     */
    public function retrieveAll()
    {
        $adapterCollection = new MetaDataSourceCollection();

        foreach ($this->fileManager->glob(Installer::BASE_PATH . self::SOURCE_PATH . '*' . self::EXTENSION) as $file) {
            // Decode
            $config     = $this->transtyper->decode($this->fileManager->fileGetContent($file));
            // Validate
            try {
                $this->arrayValidator->isValid('CrudGenerator\Metadata\MetaDataSource', $config);
            } catch (ValidationException $e) {
                continue; // Do nothing
            }

            // Hydrate
            $adapter    = $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                $config[MetaDataSource::METADATA_DAO_FACTORY]
            );

            $adapter->setMetadataDaoFactory($config[MetaDataSource::METADATA_DAO_FACTORY]);
            $adapter->setMetadataDao($config[MetaDataSource::METADATA_DAO]);

            // Test if have a config
            if (isset($config[MetaDataSource::CONFIG]) === true) {
                $adapter->setConfig($this->driverHydrator->arrayToDto($config[MetaDataSource::CONFIG]));
            }

            $adapterCollection->append($adapter);
        }

        return $adapterCollection;
    }

    /**
     * @param  MetaDataSource $source
     * @return MetaDataSource
     */
    public function save(MetaDataSource $source)
    {
        $source = $this->ask($source);

        $this->fileManager->filePutsContent(
            Installer::BASE_PATH . self::SOURCE_PATH . md5($source->getUniqueName()) . self::EXTENSION,
            json_encode($source->jsonSerialize())
        );

        return $source;
    }

    /**
     * @param  MetaDataSource $source
     * @return MetaDataSource
     */
    public function ask(MetaDataSource $source)
    {
        $notWellConfigured = true;
        do {
            $driverDescription   = $this->askDriver($source);
            $driverConfiguration = $driverDescription->getConfig();
            $driverConfiguration->setMetadataDaoFactory($source->getMetadataDaoFactory());

            foreach ($driverConfiguration->getQuestion() as $question) {
                $driverConfiguration->response(
                    $question[DriverConfig::QUESTION_ATTRIBUTE],
                    $this->context->ask(
                        new SimpleQuestion(
                            $question[DriverConfig::QUESTION_DESCRIPTION],
                            $question[DriverConfig::QUESTION_ATTRIBUTE]
                        )
                    )
                );
            }

            $source->setConfig($driverConfiguration);

            try {
                $this->isWellConfigured($source);
                $notWellConfigured = false;
            } catch (ConfigException $e) {
                $this->context->log($e->getMessage(), 'error');
            }
        } while ($notWellConfigured);

        return $source;
    }

    /**
     * @param  MetaDataSource                        $source
     * @return \CrudGenerator\Metadata\Driver\Driver
     */
    private function askDriver(MetaDataSource $source)
    {
        $isUniqueDriver = $source->isUniqueDriver();

        // Dont ask witch driver
        if ($isUniqueDriver === true) {
            $driversFactory    = $source->getDriversDescription();
            $driverDescription = $driversFactory[0];
        } else {
            // Ask wich connector to use
            $predefinedResponseCollection = new PredefinedResponseCollection();

            foreach ($source->getDriversDescription() as $description) {
                $predefinedResponseCollection->append(
                    new PredefinedResponse(
                        $description->getDefinition(),
                        $description->getDefinition(),
                        $description
                    )
                );
            }
            $question = new QuestionWithPredefinedResponse(
                'Select a connector',
                'metadataconfig_connector',
                $predefinedResponseCollection
            );
            $question->setConsumeResponse(true);
            $question->setShutdownWithoutResponse(true);

            $driverDescription = $this->context->askCollection($question);
        }

        return $driverDescription;
    }

    /**
     */
    private function isWellConfigured(MetaDataSource $source)
    {
        $driverConfig = $source->getConfig();

        if ($driverConfig === null) {
            $metadataDAOFactory = $source->getMetadataDaoFactory();
            $metadataDAOFactory::getInstance();
        } else {
            $driverFactory = $driverConfig->getDriver();
            $driver        = $driverFactory::getInstance();

            /* @var $driver \CrudGenerator\Metadata\Driver\DriverInterface */

            $driver->isValid($driverConfig);
        }
    }
}
