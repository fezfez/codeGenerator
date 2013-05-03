<?php

namespace CrudGenerator\Command;

use CrudGenerator\MetaData\MetaDataDAOFactory;
use CrudGenerator\Generators\DoctrineCrudGeneratorFactory;
use CrudGenerator\DataObject;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption;

use RuntimeException;
use InvalidArgumentException;
use ReflectionClass;


class AllCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('CrudGenerator:all')
             ->setDescription('Generate crud based on Doctrine Entity');
    }

    public function found($dir)
    {
        $implementsIModule = array();
        foreach(glob($dir . '*') as $class) {
            echo $class . "\n";
            if(!is_dir($class)) {
                $name = str_replace('/', '', strstr(strrchr($class, '/'), '.', true));
                echo $name . "\n";
                $reflect = new ReflectionClass($name);
                if($reflect->implementsInterface('CrudGenerator\Generators\Generator')) {
                    $implementsIModule[] = $class;
                }
            }
        }

        return $implementsIModule;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sm      = $this->getHelperSet()->get('serviceManager')->getServiceManager();
        $dialog  = $this->getHelperSet()->get('dialog');
        $config  = include 'config/application.config.php';

        //$implementsIModule = $this->found(__DIR__ . '/../Generators/');
        //var_dump($implementsIModule);exit;

        $metaDataDAO = MetaDataDAOFactory::getInstance($sm);
        $allMetaData = $metaDataDAO->getAllMetadata();


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

        $entity = $dialog->askAndValidate($output, "Full namespace Entity \n> ", $entityValidation, false, null, $entityChoices);


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

        $moduleName = $dialog->askAndValidate($output, "Choose a target module \n> ", $modulesValidation, false, null, $modulesChoices);

        $directoryValidation = function ($directory) use ($moduleName) {
            if (!is_dir($moduleName . '/' . $directory)) {
                throw new \InvalidArgumentException(sprintf('Directory "%s" does not exist.', $moduleName . $directory));
            }

            return $directory;
        };

        $directory   = $dialog->askAndValidate($output, 'Choose a target directory ', $directoryValidation, false, null);
        $namespace   = $dialog->ask($output, 'Choose a target namespace ');
        $writeAction = $dialog->askConfirmation($output, '<question>Do you want to generate the "write" actions ?</question> ');

        $dataObject = new DataObject();
        $dataObject->setEntity($entity)
                   ->setModule($moduleName)
                   ->setMetaData($metaDataDAO->getEntityMetadata($entity))
                   ->setWriteAction($writeAction)
                   ->setNamespace($namespace)
                   ->setDirectory($directory);

        $output->writeln("\n\n<info>Resume</info>");
        $output->writeln('<info>Entity : ' . $dataObject->getEntity(), '*</info>');
        $output->writeln('<info>Module : ' . $dataObject->getModule(), '*</info>');
        $output->writeln('<info>Target directory : ' . $dataObject->getDirectory(), '*</info>');
        $output->writeln('<info>Target namespace : ' . $dataObject->getNamespace(), '*</info>');
        $output->writeln('<info>Write action : ' . $dataObject->getWriteAction(), '*</info>');

        $doI = $dialog->askConfirmation($output, "<question>Do you confirm generation ?</question> ");

        if($doI === true) {
            $crudGenerator = DoctrineCrudGeneratorFactory::getInstance($output, 'DoctrineCrudGenerator');
            $crudGenerator->generate($dataObject);
        } else {
            throw new RuntimeException('Command aborted');
        }
    }
}
