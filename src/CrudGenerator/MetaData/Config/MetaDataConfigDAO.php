<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use ReflectionClass;
use phpDocumentor\Reflection\DocBlock;
use CrudGenerator\MetaData\Sources\MetadataConfig;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;

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
     * @param DocBlock $docBlock
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

            if (false === isset($configFile['response'])) {
                continue;
            }

            if (false === in_array($configFile[self::SOURCE_FACTORY_KEY], $allowedMetadataDAO)) {
                continue;
            }

            $adapter = $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                $configFile[self::SOURCE_FACTORY_KEY]
            );

            $driverConfig = new DriverConfig($configFile['uniqueName']);
            foreach ($configFile['response'] as $responseKey => $response) {
                $driverConfig->response($responseKey, $response);
            }
            $driverConfig->setDriver($configFile['driver']);

            $adapter->setConfig($driverConfig);
            $adapterCollection->append($adapter);
        }

        return $adapterCollection;
    }

    /**
     * @param MetadataConfig $adapterConfig
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
     * @param MetadataConfig $adapterConfig
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
                $this->context->ask($question['desc'], $question['attr'])
            );
        }

        return $driverConfiguration;
    }

    /**
     * @param MetadataConfig $adapterConfig
     * @return boolean
     */
    private function isValid(DriverConfig $driverConfig)
    {
        $driverFactory = $driverConfig->getDriver();
        $driver        = $driverFactory::getInstance();

        $driver->isValid($driverConfig);
    }
}
