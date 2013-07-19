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
     * @var OutputInterface Output
     */
    private $output = null;
    /**
     * @var DialogHelper Dialog
     */
    private $dialog = null;
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;

    /**
     * Config a Dataobject for particulary Metadata adpater based on AbstractConfig
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

        if ($this->fileManager->isFile($configPath)) {
            $this->output->writeln('<info>Adapter config found !</info>');
            $configured = unserialize($this->fileManager->fileGetContent($configPath));
            $configured = $this->read($configured);
        } else {
            $this->output->writeln('For use this adapter you to config it before');
            $configured = $this->read($adapterConfig);
            $this->output->writeln('<info>Adapter fully configured !</info>');
            $this->fileManager->unlink($configPath);
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

            while ($continue) {
                try {
                    $adapterConfig->test($this->output);
                    $continue = false;
                    break;
                } catch (ConfigException $e) {
                    if ($first === false) {
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
            if ($propName === 'definition') {
                continue;
            }
            $propSetter = 'set' . ucfirst($propName);
            $value      = $this->dialog->ask($this->output, 'Choose a "' . $propName . '" : ');
            $adapterConfig->$propSetter($value);
        }

        return $adapterConfig;
    }
}
