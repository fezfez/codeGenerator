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


class UpToDateCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('CrudGenerator:upToDate')
             ->setDescription('Detect if code is up to date');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sm      = $this->getHelperSet()->get('serviceManager')->getServiceManager();
        $dialog  = $this->getHelperSet()->get('dialog');
        $config  = include 'config/application.config.php';

        new DataObject();
        $metaDataDAO = MetaDataDAOFactory::getInstance($sm);
        $allMetaData = $metaDataDAO->getAllMetadata();

        $classList = array();
        foreach ($allMetaData as $number => $class) {
            $classList[$class->getName()] = $class;
        }

        if(!glob('data/crudGeneratorHistory/*')) {
            $output->writeln('<question>Empty history</question>');
            return;
        }
        foreach(glob('data/crudGeneratorHistory/*') as $file) {
            $dataObject = unserialize(file_get_contents($file));

            if(!isset($classList[$dataObject->getMetadata()->getName()])) {
                $output->writeln('<error>' . $dataObject->getMetadata()->getName() . ' does not exist</error>');
                continue;
            } else {
                $newMapping = $classList[$dataObject->getMetadata()->getName()]->fieldMappings;
                $oldMapping = $dataObject->getMetadata()->fieldMappings;

                if($newMapping === $oldMapping) {
                    $output->writeln('<info>' . $dataObject->getMetadata()->getName() . ' is up to date !</info>');
                } else {
                    $diffs = $this->array_diff_assoc_recursive($newMapping, $oldMapping);

                    $output->writeln('<error>' . $dataObject->getMetadata()->getName()  . ' is NOT up to date</error>');
                    $output->writeln('<error>Diff</error>');

                    foreach($diffs as $key => $diff) {
                        $output->writeln('<error> - ' . $key . '</error>');
                        if(!isset($oldMapping[$key])) {
                            $output->writeln('<error> -- before : this key does not exist</error>');
                        } else {
                            foreach($diff as $toto => $test) {
                                $output->writeln('<error> -- definition : ' . $toto . '</error>');
                                $output->writeln('<error> -------- before : ' . var_export($oldMapping[$key][$toto], true) . '</error>');
                                $output->writeln('<error> -------- after  : ' . var_export($newMapping[$key][$toto], true) . '</error>');
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * http://www.php.net/manual/fr/function.array-diff-assoc.php#111675
     */
    private function array_diff_assoc_recursive($array1, $array2)
    {
        $difference = array();
        foreach($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
                    if (!empty($new_diff)) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif(!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }
}
