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
use CrudGenerator\History\HistoryManager;

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
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var CodeGeneratorFactory
     */
    private $codeGeneratorFactory = null;

    public function __construct($name = null, HistoryManager $historyManager = null, CodeGeneratorFactory $codeGeneratorFactory = null)
    {
        $this->historyManager       = (null === $historyManager) ? HistoryFactory::getInstance() : $historyManager;
        $this->codeGeneratorFactory = (null === $codeGeneratorFactory) ? new CodeGeneratorFactory() : $codeGeneratorFactory;
        parent::__construct($name);
    }
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
        $dialog     = $this->getHelperSet()->get('dialog');
        $history    = $this->historyQuestion($output, $dialog);
        $dataObject = $history->getDataObject();

        $crudGenerator = $this->codeGeneratorFactory->create($output, $dialog, $dataObject->getGenerator());

        $output->writeln("<info>Resume</info>");
        $output->writeln('<info>Entity : ' . $dataObject->getEntity(), '*</info>');
        $output->writeln('<info>Module : ' . $dataObject->getModule(), '*</info>');
        $output->writeln("<info>Generator : " . $crudGenerator->getDefinition(), "*</info>");

        $doI = $dialog->askConfirmation(
            $output,
            "\n<question>Do you confirm generation (may others question generator ask you) ?</question> "
        );

        if ($doI === true) {
            $dataObject = $crudGenerator->generate($dataObject);
        } else {
            throw new RuntimeException('Command aborted');
        }
    }

    /**
     * @param unknown_type $output
     * @param unknown_type $dialog
     * @return \CrudGenerator\History\History
     */
    private function historyQuestion($output, $dialog)
    {
        $historyCollection = $this->historyManager->findAll();

        $output->writeln('<question>History list</question>');
        $historyChoices = array();
        foreach ($historyCollection as $history) {
            $output->writeln('<comment>' . $history->getName() . '</comment>');
            $historyChoices[$history->getName()] = $history;
        }

        $choice = $dialog->select(
            $output,
            "History to regenerate \n> ",
            array_keys($historyChoices),
            0
        );

        return $historyChoices[$choice];
    }
}
