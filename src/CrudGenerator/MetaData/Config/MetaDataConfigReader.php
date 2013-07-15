<?php
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\MetaData\AbstractConfig;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\FileManager;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

use ReflectionClass;
use ReflectionProperty;

/**
 * Config a Dataobject for particulary Metadata adpater based on AbstractConfig
 *
 * @author Stéphane Demonchaux
 */
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
     * @var FileManager File manager
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
     * Config a Dataobject for particulary Metadata adpater based on AbstractConfig
     *
     * @param AbstractConfig $adapterConfig
     * @return \CrudGenerator\MetaData\AbstractConfig
     */
    public function config(AbstractConfig $adapterConfig)
    {
        $configPath = 'data/crudGeneratorHistory/' . md5(get_class($adapterConfig));

        if($this->fileManager->isFile($configPath)) {
            $this->output->writeln('<info>Adapter config found !</info>');
            $configured = unserialize($this->fileManager->fileGetContent($configPath));
            $configured = $this->read($configured);
        } else {
            $this->output->writeln('For use this adapter you to config it before');
            $configured = $this->read($adapterConfig);
            $this->output->writeln('<info>Adapter fully configured !</info>');
            $this->fileManager->filePutsContent($configPath, serialize($configured));
        }

        return $configured;
    }

    /**
     * Test if Dataobject is well configured
     *
     * @param AbstractConfig $adapterConfig
     * @return AbstractConfig
     */
    private function read(AbstractConfig $adapterConfig)
    {
        $adapterConfig = clone $adapterConfig;

        try {
            $adapterConfig->test($this->output);
            return $adapterConfig;
        } catch (ConfigException $e) {

            $continue = true;
            $first    = true;

            while($continue) {
                try {
                    $result = $adapterConfig->test($this->output);
                    $continue = false;
                    break;
                } catch (ConfigException $e) {
                    if($first === false) {
                        $this->output->writeln("<error>" . $e->getMessage() . "</error>");
                    }
                }

                $first = false;
                $adapterConfig = $this->write($adapterConfig);
            }

            return $adapterConfig;
        }
    }

    /**
     * Write data into Dataobject
     *
     * @param AbstractConfig $adapterConfig
     * @return AbstractConfig
     */
    private function write(AbstractConfig $adapterConfig)
    {
        $adapterConfig = clone $adapterConfig;
        $reflect = new ReflectionClass($adapterConfig);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($props as $prop) {
            $propName = $prop->getName();
            if($propName === 'definition') {
                continue;
            }
            $propSetter = 'set' . ucfirst($propName);
            $value      = $this->dialog->ask($this->output, 'Choose a "' . $propName . '" : ');
            $adapterConfig->$propSetter($value);
        }

        return $adapterConfig;
    }
}
