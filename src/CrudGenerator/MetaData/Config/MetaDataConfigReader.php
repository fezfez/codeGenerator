<?php
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\MetaData\AbstractConfig;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\FileManager;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

use ReflectionClass;
use ReflectionProperty;

class MetaDataConfigReader
{
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;

    /**
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param FileManager $fileManager
     */
    public function __construct(OutputInterface $output, DialogHelper $dialog, FileManager $fileManager)
    {
        $this->output      = $output;
        $this->dialog      = $dialog;
        $this->fileManager = $fileManager;
    }

    /**
     * @param AbstractConfig $adapterConfig
     * @return \CrudGenerator\MetaData\AbstractConfig
     */
    public function config(AbstractConfig $adapterConfig)
    {
        $configPath = 'data/crudGeneratorHistory/' . md5(get_class($adapterConfig));

        if(!is_file($configPath)) {
            $this->output->writeln('For use this adapter you to config it before');
            $configured = $this->read($adapterConfig);
            $this->output->writeln('<info>Adapter fully configured !</info>');
            $this->fileManager->filePutsContent($configPath, serialize($configured));
        } elseif(null !== $adapterConfig) {
            $this->output->writeln('<info>Adapter config found !</info>');
            $configured = unserialize($this->fileManager->fileGetContent($configPath));
            $configured = $this->read($configured);
        }

        return $configured;
    }

    /**
     * @param AbstractConfig $adapterConfig
     * @return AbstractConfig
     */
    private function read(AbstractConfig $adapterConfig)
    {
        $adapterConfig = clone $adapterConfig;
        try {
            return $adapterConfig->test($this->output);
        } catch (ConfigException $e) {
            $adapterConfig = $this->write($adapterConfig);
        }

        $output = $this->output;
        $dialog = $this->dialog;

        $write = function (AbstractConfig $adapterConfig) use($dialog, $output) {
            $adapterConfig = clone $adapterConfig;
            $reflect = new ReflectionClass($adapterConfig);
            $props   = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);

            foreach ($props as $prop) {
                $propName = $prop->getName();
                if($propName === 'definition') {
                    continue;
                }
                $propSetter = 'set' . ucfirst($propName);
                $value      = $dialog->ask($output, 'Choose a "' . $propName . '" : ');
                $adapterConfig->$propSetter($value);
            }

            return $adapterConfig;
        };
        /**
         *  @return AbstractConfig
         */
        $testAdapterConfig = function() use($adapterConfig, $output, $write) {
            $continue = true;
            while($continue) {
                try {
                    $adapterConfig->test($output);
                    $continue = false;
                } catch (ConfigException $e) {
                }

                return $write($adapterConfig);
            }
        };

        return $testAdapterConfig();
    }
}
