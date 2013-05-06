<?php

namespace CrudGenerator\Command;

use CrudGenerator\MetaData\MetaDataDAOFactory;
use CrudGenerator\Generators\DoctrineCrudGeneratorFactory;
use CrudGenerator\DataObject;
use CrudGenerator\FileManager;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption;

use RuntimeException;
use InvalidArgumentException;
use ReflectionClass;


class CreateCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('CrudGenerator:create')
             ->setDescription('Generate code based on Doctrine Entity');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sm      = $this->getHelperSet()->get('serviceManager')->getServiceManager();
        $dialog  = $this->getHelperSet()->get('dialog');
        $config  = include 'config/application.config.php';

        $metaDataDAO = MetaDataDAOFactory::getInstance($sm);

        $entity     = $this->entityQuestion($output, $dialog, $metaDataDAO->getAllMetadata());
        $moduleName = $this->moduleQuestion($output, $dialog);

        $dataObject = new DataObject();
        $dataObject->setEntity($entity)
                   ->setModule($moduleName)
                   ->setMetaData($metaDataDAO->getEntityMetadata($entity));

        $output->writeln("\n\n<info>Resume</info>");
        $output->writeln('<info>Entity : ' . $dataObject->getEntity(), '*</info>');
        $output->writeln('<info>Module : ' . $dataObject->getModule(), '*</info>');

        $doI = $dialog->askConfirmation($output, "<question>Do you confirm generation ?</question> ");

        if($doI === true) {
            $fileManager = new FileManager();
            if(!is_dir('data/crudGeneratorHistory')) {
                $fileManager->mkdir('data/crudGeneratorHistory');
            }
            if(is_file('data/crudGeneratorHistory/' . md5($entity))) {
                unlink('data/crudGeneratorHistory/' . md5($entity));
            }
            $fileManager->filePutsContent('data/crudGeneratorHistory/' . md5($entity), serialize($dataObject));

            $crudGenerator = DoctrineCrudGeneratorFactory::getInstance($output, $input, $dialog, '\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator');
            $output->writeln("\n\n<info>" . $crudGenerator->getDefinition() . "</info>");
            $crudGenerator->generate($dataObject);
        } else {
            throw new RuntimeException('Command aborted');
        }
    }

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

        return $dialog->askAndValidate($output, "Choose a target module \n> ", $modulesValidation, false, null, $modulesChoices);
    }

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

        return $dialog->askAndValidate($output, "Full namespace Entity \n> ", $entityValidation, false, null, $entityChoices);
    }
}
