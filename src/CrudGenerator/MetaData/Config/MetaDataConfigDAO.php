<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\MetaData\Sources\MetadataConfig;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\Context\SimpleQuestion;
use CrudGenerator\MetaData\Driver\DriverValidator;
use CrudGenerator\MetaData\MetaDataSourceValidator;
use CrudGenerator\Utils\Transtyper;
use CrudGenerator\MetaData\Driver\DriverHydrator;
use KeepUpdate\ArrayValidator;

class MetaDataConfigDAO
{
    /**
     * @var string Config path
     */
    const PATH = 'data/crudGenerator/Config/';
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
     * @param FileManager $fileManager
     * @param Transtyper $transtyper
     * @param ArrayValidator $arrayValidator
     * @param MetaDataSourceHydrator $metaDataSourceHydrator
     * @param DriverHydrator $driverHydrator
     * @param ContextInterface $context
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
     * @return \CrudGenerator\MetaData\MetaDataSourceCollection
     */
    public function retrieveAll()
    {
        $adapterCollection = new MetaDataSourceCollection();

        foreach ($this->fileManager->glob(self::PATH . '*' . self::EXTENSION) as $file) {
            // Decode
            $config     = $this->transtyper->decode($this->fileManager->fileGetContent($file));
            // Validate
            $this->arrayValidator->isValid('CrudGenerator\MetaData\MetaDataSource', $config);
            // Hydrate
            $adapter    = $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                $config[MetaDataSource::METADATA_DAO_FACTORY]
            );

            // Test if have a config
            if (isset($config[DriverConfig::FACTORY]) === false) {
                $adapter->setConfig($this->driverHydrator->arrayToDto($config));
            }

            $adapterCollection->append($adapter);
        }

        return $adapterCollection;
    }

    /**
     * @param MetaDataSource $source
     * @return boolean
     */
    public function save(MetaDataSource $source)
    {
        $source = $this->ask($source);

        $this->isWellConfigured($source);

        $this->fileManager->filePutsContent(
            self::PATH . md5($source->getUniqueName()) . self::EXTENSION,
            json_encode($source->jsonSerialize())
        );

        return $source;
    }

    /**
     * @param MetaDataSource $source
     * @return MetaDataSource
     */
    public function ask(MetaDataSource $source)
    {
        $isUniqueDriver = $source->isUniqueDriver();

        // Dont ask witch driver
        if($isUniqueDriver === true) {
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
            $question->setShutdownWithoutResponse(true);

            $driverDescription = $this->context->askCollection($question);
        }

        /* @var $driverDescription \CrudGenerator\MetaData\Driver\Driver */

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

        return $source->setConfig($driverConfiguration);
    }

    /**
     * @param DriverConfig $driverConfig
     */
    private function isWellConfigured(MetaDataSource $source)
    {
        $config = $source->getConfig();

        if ($config === null) {
            $metadataDAOFactory = $source->getMetadataDaoFactory();
            $metadataDAOFactory::getInstance();
        } else {
            $driverConfig  = $source->getConfig();
            $driverFactory = $driverConfig->getDriver();
            $driver        = $driverFactory::getInstance();

            /* @var $driver \CrudGenerator\MetaData\Driver\DriverInterface */

            $driver->isValid($driverConfig);
        }
    }
}
