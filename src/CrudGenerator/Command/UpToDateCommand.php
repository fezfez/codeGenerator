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

use CrudGenerator\MetaData\MetaDataDAOFactory;
use CrudGenerator\DataObject;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Check if the generated code is up to date
 *
 * @author Stéphane Demonchaux
 */
class UpToDateCommand extends Command
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('CrudGenerator:upToDate')
             ->setDescription('Detect if code is up to date');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     * @param InputInterface $input
     * @param OutputInterface $output
     */
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

        if (!glob('data/crudGeneratorHistory/*')) {
            $output->writeln('<question>Empty history</question>');
            return;
        }
        foreach (glob('data/crudGeneratorHistory/*') as $file) {
            $dataObject = unserialize(file_get_contents($file));

            if (!isset($classList[$dataObject->getMetadata()->getName()])) {
                $output->writeln('<error>' . $dataObject->getMetadata()->getName() . ' does not exist</error>');
                continue;
            } else {
                $newMapping = $classList[$dataObject->getMetadata()->getName()]->getColumnCollection();
                $oldMapping = $dataObject->getMetadata()->getColumnCollection();

                if ($newMapping === $oldMapping) {
                    $output->writeln('<info>' . $dataObject->getMetadata()->getName() . ' is up to date !</info>');
                } else {
                    $diffs = $this->arrayDiffAssocRecursive($newMapping, $oldMapping);

                    $output->writeln('<error>' . $dataObject->getMetadata()->getName()  . ' is NOT up to date</error>');
                    $output->writeln('<error>Diff</error>');

                    $textKeyDefinition = (function ($when, $var) {
                        return '<error> -------- ' . $when . ' : ' . $var . '</error>';
                    });

                    foreach ($diffs as $key => $diff) {
                        $output->writeln('<error> - ' . $key . '</error>');
                        if (!isset($oldMapping[$key])) {
                            $output->writeln('<error> -- before : this key does not exist</error>');
                        } else {
                            foreach ($diff as $toto => $test) {
                                $output->writeln('<error> -- definition : ' . $toto . '</error>');
                                $output->writeln(
                                    $textKeyDefinition('before', var_export($oldMapping[$key][$toto], true))
                                );
                                $output->writeln(
                                    $textKeyDefinition('after', var_export($newMapping[$key][$toto], true))
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * http://www.php.net/manual/fr/function.array-diff-assoc.php#111675
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private function arrayDiffAssocRecursive($array1, $array2)
    {
        $difference = array();
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiffAssocRecursive($value, $array2[$key]);
                    if (!empty($new_diff)) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }
}
