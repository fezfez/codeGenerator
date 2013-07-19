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
use CrudGenerator\Adapter\AdapterFinderFactory;
use CrudGenerator\Generators\GeneratorFinderFactory;
use CrudGenerator\History\HistoryFactory;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use RuntimeException;
use InvalidArgumentException;

/**
 * Regenerate command
 *
 * @author Stéphane Demonchaux
 */
class RegenerateCommand extends Command
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('CodeGenerator:regenerate')
             ->setDescription('Regenerate code');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog          = $this->getHelperSet()->get('dialog');
        $historyManager  = HistoryFactory::getInstance();

        $historyCollection = $historyManager->findAll();

        $history = $this->historyQuestion($output, $dialog, $historyCollection);

        $dataObject = $history->getDataObject();

        $crudGenerator = CodeGeneratorFactory::getInstance($output, $input, $dialog, $dataObject->getGenerator());

        $output->writeln("<info>Resume</info>");
        $output->writeln('<info>Entity : ' . $dataObject->getEntity(), '*</info>');
        $output->writeln('<info>Module : ' . $dataObject->getModule(), '*</info>');
        $output->writeln("<info>Generator : " . $crudGenerator->getDefinition(), "*</info>");

        $doI = $dialog->askConfirmation(
            $output,
            "\n<question>Do you confirm generation (may others question generator ask you) ?</question> "
        );

        if ($doI === true) {
            $historyManager = HistoryFactory::getInstance();

            $dataObject = $crudGenerator->generate($dataObject);
        } else {
            throw new RuntimeException('Command aborted');
        }
    }

    private function historyQuestion($output, $dialog, $allHistory)
    {
        $output->writeln('<question>History list</question>');
        $historyChoices = array();
        foreach ($allHistory as $history) {
            $output->writeln('<comment>' . $history->getName() . '</comment>');
            $historyChoices[] = $history->getName();
        }

        $historyValidation = function ($history) use ($historyChoices, $allHistory) {
            foreach ($allHistory as $historyDataobject) {
                $historyName = $historyDataobject->getName();
                if ($historyName === $history) {
                    return $historyDataobject;
                }
            }

            throw new \InvalidArgumentException(sprintf('History "%s" is invalid.', $history));
        };

        return $dialog->askAndValidate(
            $output,
            "History to regenerate \n> ",
            $historyValidation,
            false,
            null,
            $historyChoices
        );
    }
}
