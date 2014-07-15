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
use CrudGenerator\MetaData\Sources\MetaDataConfig;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use ReflectionClass;
use ReflectionProperty;

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
            'CrudGenerator\MetaData\Sources\MetaDataDAOFactory'
        );

        $adapterCollection = new MetaDataSourceCollection();

        foreach ($this->fileManager->glob(self::PATH . '*' . self::EXTENSION) as $file) {
            $configFile = (array) json_decode($this->fileManager->fileGetContent($file));

            if (false === isset($configFile['metaDataDAO'])) {
                continue;
            }

            if (false === in_array($configFile['metaDataDAO'], $allowedMetadataDAO)) {
                continue;
            }

            $adapter = $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                $configFile['metaDataDAO']
            );
            $config = $adapter->getConfig();
            $configMethods = get_class_methods($config);
            foreach ($configFile as $configAttribute => $configValue) {
                $configAttr = 'set' . ucfirst($configAttribute);
                if (true === in_array($configAttr, $configMethods)) {
                    $config->$configAttr($configValue);
                }
            }

            $adapter->setConfig($config);
            $adapterCollection->append($adapter);
        }

        return $adapterCollection;
    }

    public function retrieve()
    {

    }

    /**
     * @param MetaDataConfig $adapterConfig
     * @return boolean
     */
    public function save(MetaDataConfig $adapterConfig)
    {
        $adapterConfig = $this->ask($adapterConfig);
        $this->isValid($adapterConfig);

        $configPath = self::PATH . $adapterConfig->getUniqueName() . self::EXTENSION;

        $this->fileManager->filePutsContent($configPath, json_encode($adapterConfig->jsonSerialize()));
        return true;
    }

    /**
     * @param MetaDataConfig $adapterConfig
     * @return $adapterConfig
     */
    public function ask(MetaDataConfig $adapterConfig)
    {
        $adapterConfig = clone $adapterConfig;
        $reflect       = new ReflectionClass($adapterConfig);
        $props         = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $propName = $prop->getName();
            if ($propName === 'definition') {
                continue;
            }

            $value = $this->context->ask('Choose a "' . $propName . '"', $propName);

            $attribute = 'set' . ucfirst($propName);
            $adapterConfig->$attribute($value);
        }

        return $adapterConfig;
    }

    /**
     * @param MetaDataConfig $adapterConfig
     * @return boolean
     */
    private function isValid(MetaDataConfig $adapterConfig)
    {
        $adapterConfig->test();
        return true;
    }
}