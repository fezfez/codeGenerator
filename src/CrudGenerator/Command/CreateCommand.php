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
namespace CrudGenerator\Command;

use CrudGenerator\Generators\CodeGeneratorFactory;
use CrudGenerator\DataObject;
use CrudGenerator\FileManager;
use CrudGenerator\MetaData\Config\MetaDataConfigReaderFactory;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use RuntimeException;
use InvalidArgumentException;

/**
 * Generator command
 *
 * @author Stéphane Demonchaux
 */
class CreateCommand extends Command
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('CodeGenerator:create')
             ->setDescription('Generate code based on database connection');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog      = $this->getHelperSet()->get('dialog');
        $fileManager = new FileManager();

        $adapter             = $this->adapterQuestion($output, $dialog);
        $adapterConfig       = $adapter->getConfig();
        $adapterConfigurator = MetaDataConfigReaderFactory::getInstance($output, $dialog);

        if(null !== $adapterConfig) {
            $adapterConfig = $adapterConfigurator->config($adapterConfig);
        }

        $adapterFactory = $adapter->getFactory();
        if(null !== $adapterConfig) {
            $adapterDAO = $adapterFactory::getInstance($adapterConfig);
        } else {
            $adapterDAO = $adapterFactory::getInstance();
        }

        $entity     = $this->entityQuestion($output, $dialog, $adapterDAO->getAllMetadata());
        $moduleName = $this->moduleQuestion($output, $dialog);
        $generator  = $this->generatorQuestion($output, $input, $dialog);

        $dataObject = new DataObject();
        $dataObject->setEntity($entity)
                   ->setModule($moduleName)
                   ->setMetaData($adapterDAO->getMetadataFor($entity));

        $crudGenerator = CodeGeneratorFactory::getInstance($output, $input, $dialog, $generator);

        $output->writeln("<info>Resume</info>");
        $output->writeln('<info>Entity : ' . $dataObject->getEntity(), '*</info>');
        $output->writeln('<info>Module : ' . $dataObject->getModule(), '*</info>');
        $output->writeln("<info>Generator : " . $crudGenerator->getDefinition(), "*</info>");

        $doI = $dialog->askConfirmation(
            $output,
            "\n<question>Do you confirm generation (may others question generator ask you) ?</question> "
        );

        if ($doI === true) {
            if (!is_dir('data/crudGeneratorHistory')) {
                $fileManager->mkdir('data/crudGeneratorHistory');
            }
            if (is_file('data/crudGeneratorHistory/' . md5($entity))) {
                unlink('data/crudGeneratorHistory/' . md5($entity));
            }
            $fileManager->filePutsContent('data/crudGeneratorHistory/' . md5($entity), serialize($dataObject));

            $crudGenerator->generate($dataObject);
        } else {
            throw new RuntimeException('Command aborted');
        }
    }

    /**
     * Ask wich adapter you want to use
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @throws \InvalidArgumentException
     * @return AdapterDataObject
     */
    private function adapterQuestion($output, $dialog)
    {
        $adapterFinder   = new \CrudGenerator\Adapter\AdapterFinder();
        $adaptersCollection = $adapterFinder->getAllAdapters();
        $output->writeln('<question>Adapters list</question>');
        foreach($adaptersCollection as $adapter) {
            $falseDependencies = $adapter->getFalseDependencies();

            if(!empty($falseDependencies)) {
                $output->writeln('<error>Dependencies not complet for use adapter "' . $adapter->getName() . '" caused by</error>');
                $output->writeln('<error> * ' . $falseDependencies . '</error>');
            } else {
                $output->writeln('<comment>' . $adapter->getDefinition() . '</comment>');
                $adaptersChoices[] = $adapter->getName();
            }
        }

        $adapterValidation = function ($adapter) use ($adaptersChoices, $adaptersCollection) {
            foreach($adaptersCollection as $adapterDataobject) {
                $adapterName = $adapterDataobject->getName();
                if($adapterName === $adapter) {
                    return $adapterDataobject;
                }
            }

            throw new \InvalidArgumentException(sprintf('Adapter "%s" is invalid.', $adapter));
        };

        return $dialog->askAndValidate(
            $output,
            "Choose an adapter \n> ",
            $adapterValidation,
            false,
            null,
            $adaptersChoices
        );
    }

    /**
     * Ask in wich module you want to write
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @throws \InvalidArgumentException
     * @return string
     */
    private function moduleQuestion($output, $dialog)
    {
        $output->writeln('<question>Modules list</question>');

        $modulesChoices = glob('module/*');
        foreach ($modulesChoices as $moduleName) {
            $output->writeln('<comment>  ' . $moduleName . '</comment>');
        }

        $modulesValidation = function ($module) use ($modulesChoices) {
            if (!in_array($module, array_values($modulesChoices))) {
                throw new \InvalidArgumentException(sprintf('Module "%s" is invalid.', $module));
            }

            return $module;
        };

        return $dialog->askAndValidate(
            $output,
            "Choose a target module \n> ",
            $modulesValidation,
            false,
            null,
            $modulesChoices
        );
    }

    /**
     * Ask wich generator you want to use
     *
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param DialogHelper $dialog
     * @throws \InvalidArgumentException
     * @return string
     */
    private function generatorQuestion($output, $input, $dialog)
    {
        $crudFinder = new \CrudGenerator\Generators\GeneratorFinder();
        $generators = $crudFinder->getAllClasses();

        $output->writeln('<question>Chose a generator</question>');
        foreach ($generators as $number => $generatorClassName) {
            $generator = CodeGeneratorFactory::getInstance($output, $input, $dialog, $generatorClassName);
            $output->writeln('<comment>' . $number . '. ' . $generator->getDefinition() . '</comment>');
        }

        $generatorsValidation = function ($module) use ($generators) {
            if (!isset($generators[$module])) {
                throw new \InvalidArgumentException(sprintf('Generator "%s" is invalid.', $module));
            }

            return $generators[$module];
        };

        return $dialog->askAndValidate($output, "Choose a generators \n> ", $generatorsValidation, false);
    }

    /**
     * Ask wich entity you want to use
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param array $allMetaData
     * @throws InvalidArgumentException
     * @return string
     */
    private function entityQuestion($output, $dialog, $allMetaData)
    {
        $output->writeln('<question>Entities list</question>');
        $entityChoices = array();
        foreach ($allMetaData as $number => $class) {
            $output->writeln('<comment>  ' . $class->getName() . '</comment>');
            $entityChoices[] = $class->getName();
        }

        $entityValidation = function ($entity) use ($entityChoices) {
            if (!in_array($entity, array_values($entityChoices))) {
                throw new InvalidArgumentException(sprintf('Entity "%s" is invalid.', $entity));
            }

            return $entity;
        };

        return $dialog->askAndValidate(
            $output,
            "Full namespace Entity \n> ",
            $entityValidation,
            false,
            null,
            $entityChoices
        );
    }
}
