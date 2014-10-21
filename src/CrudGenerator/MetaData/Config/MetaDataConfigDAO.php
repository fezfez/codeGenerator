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
     * @var string
     */
    const SOURCE_FACTORY_KEY = 'metadataDaoFactory';
    /**
     * @var string
     */
    const DRIVER_FACTORY_KEY = 'driver';
    /**
     * @var string
     */
    const RESPONSE_KEY = 'response';
    /**
     * @var string
     */
    const UNIQUE_NAME_KEY = 'uniqueName';

    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var ContextInterface context
     */
    private $context = null;
    /**
     * @var MetaDataSourceHydrator MetaData Source Hydrator
     */
    private $metaDataSourceHydrator = null;
    /**
     * @var ClassAwake Class awake
     */
    private $classAwake = null;

    /**
     * @param ClassAwake $classAwake
     * @param FileManager $fileManager
     * @param MetaDataSourceHydrator $metaDataSourceHydrator
     * @param ContextInterface $context
     */
    public function __construct(
        ClassAwake $classAwake,
        FileManager $fileManager,
        MetaDataSourceHydrator $metaDataSourceHydrator,
        ContextInterface $context
    ) {
        $this->classAwake             = $classAwake;
        $this->fileManager            = $fileManager;
        $this->metaDataSourceHydrator = $metaDataSourceHydrator;
        $this->context                = $context;
    }

    /**
     * Retrieve all config
     */
    public function retrieveAll()
    {
        $allowedMetadataDAO = $this->classAwake->wakeByInterfaces(
            array(
                __DIR__ . '/../Sources/'
            ),
            'CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface'
        );

        $adapterCollection = new MetaDataSourceCollection();

        foreach ($this->fileManager->glob(self::PATH . '*' . self::EXTENSION) as $file) {
            $configFile = (array) json_decode($this->fileManager->fileGetContent($file));

            if (false === isset($configFile[self::SOURCE_FACTORY_KEY])) {
                continue;
            }

            if (false === isset($configFile[self::RESPONSE_KEY])) {
                continue;
            }

            if (false === in_array($configFile[self::SOURCE_FACTORY_KEY], $allowedMetadataDAO)) {
                continue;
            }

            $adapter = $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                $configFile[self::SOURCE_FACTORY_KEY]
            );

            $driverConfig = new DriverConfig($configFile[self::UNIQUE_NAME_KEY]);
            foreach ($configFile[self::RESPONSE_KEY] as $responseKey => $response) {
                $driverConfig->response($responseKey, $response);
            }
            $driverConfig->setDriver($configFile[self::DRIVER_FACTORY_KEY]);

            $adapter->setConfig($driverConfig);
            $adapterCollection->append($adapter);
        }

        return $adapterCollection;
    }

    /**
     * @param MetaDataSource $adapterConfig
     * @return boolean
     */
    public function save(MetaDataSource $adapterConfig)
    {
        $adapterConfig = $this->ask($adapterConfig);
        $this->isValid($adapterConfig);

        $configPath = self::PATH . md5($adapterConfig->getUniqueName()) . self::EXTENSION;

        $this->fileManager->filePutsContent($configPath, json_encode($adapterConfig->jsonSerialize()));
        return true;
    }

    /**
     * @return $adapterConfig
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

            foreach ($source->getConnectors() as $description) {
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

        $driverConfiguration = $driverDescription->getConfig();
        $driverConfiguration->setMetadataDaoFactory($source->getMetadataDaoFactory());

        foreach ($driverConfiguration->getQuestion() as $question) {
            $driverConfiguration->response(
                $question['attr'],
                $this->context->ask(new SimpleQuestion($question['desc'], $question['attr']))
            );
        }

        return $driverConfiguration;
    }

    /**
     * @param MetadataConfig $driverConfig
     * @return boolean|null
     */
    private function isValid(DriverConfig $driverConfig)
    {
        $driverFactory = $driverConfig->getDriver();
        $driver        = $driverFactory::getInstance();

        $driver->isValid($driverConfig);
    }
}
