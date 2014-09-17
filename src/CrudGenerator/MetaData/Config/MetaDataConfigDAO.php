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
     * @var DocBlock
     */
    private $docBlock = null;

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
        DocBlock $docBlock,
        ContextInterface $context
    ) {
        $this->classAwake             = $classAwake;
        $this->fileManager            = $fileManager;
        $this->metaDataSourceHydrator = $metaDataSourceHydrator;
        $this->docBlock               = $docBlock;
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

            if (false === in_array($configFile[self::SOURCE_FACTORY_KEY], $allowedMetadataDAO)) {
                continue;
            }

            $adapter = $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                $configFile[self::SOURCE_FACTORY_KEY]
            );

            $adapter->setConfig($this->arrayToDto($configFile, $adapter->getConfig()));
            $adapterCollection->append($adapter);
        }

        return $adapterCollection;
    }

    /**
     * @param array $configFile
     * @param MetaDataConfigInterface $config
     * @return MetaDataConfigInterface
     */
    private function arrayToDto(array $configFile, MetaDataConfigInterface $config)
    {
        $configMethods = get_class_methods($config);
        foreach ($configFile as $configAttribute => $configValue) {
            $configAttr = 'set' . ucfirst($configAttribute);
            if (true === in_array($configAttr, $configMethods)) {
                $config->$configAttr($configValue);
            }
        }

        return $config;
    }

    public function retrieve()
    {

    }

    /**
     * @param MetaDataConfigInterface $adapterConfig
     * @return boolean
     */
    public function save(MetaDataConfigInterface $adapterConfig)
    {
        $adapterConfig = $this->ask($adapterConfig);
        $this->isValid($adapterConfig);

        $configPath = self::PATH . $adapterConfig->getUniqueName() . self::EXTENSION;

        $this->fileManager->filePutsContent($configPath, json_encode($adapterConfig->jsonSerialize()));
        return true;
    }

    /**
     * @param MetaDataConfigInterface $adapterConfig
     * @return $adapterConfig
     */
    public function ask(MetaDataConfigInterface $adapterConfig)
    {
        $adapterConfig = clone $adapterConfig;
        $reflect       = new ReflectionClass($adapterConfig);
        $props         = $reflect->getProperties();

        foreach ($props as $prop) {
            $propName = $prop->getName();
            if (substr($propName, 0, 6) !== 'config') {
                continue;
            }

            $docBlock  = new DocBlock($prop->getDocComment());
            $value     = $this->context->ask($docBlock->getText(), $propName);
            $attribute = 'set' . ucfirst($propName);

            $adapterConfig->$attribute($value);
        }

        return $adapterConfig;
    }

    /**
     * @param MetaDataConfigInterface $adapterConfig
     * @return boolean
     */
    private function isValid(MetaDataConfigInterface $adapterConfig)
    {
        $adapterConfig->test();
        return true;
    }
}
