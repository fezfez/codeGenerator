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
use CrudGenerator\Command\Questions\HistoryQuestion;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Regenerate command
 *
 * @author Stéphane Demonchaux
 */
class RegenerateCommand extends Command
{
    /**
     * @var DialogHelper
     */
    private $dialog               = null;
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var HistoryQuestion
     */
    private $historyQuestion      = null;
    /**
     * @var CodeGeneratorFactory
     */
    private $codeGeneratorFactory = null;

    /**
     * @param DialogHelper $dialog
     * @param HistoryQuestion $historyManager
     * @param CodeGeneratorFactory $codeGeneratorFactory
     */
    public function __construct(
        DialogHelper $dialog,
        OutputInterface $output,
        HistoryQuestion $historyQuestion,
        CodeGeneratorFactory $codeGeneratorFactory
    ) {
        $this->dialog               = $dialog;
        $this->output               = $output;
        $this->historyQuestion      = $historyQuestion;
        $this->codeGeneratorFactory = $codeGeneratorFactory;
        parent::__construct('regenerate');
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
        $histories    = $this->historyQuestion->ask();

        foreach ($histories as $dataObject) {

            $crudGenerator = $this->codeGeneratorFactory->create($this->output, $this->dialog, $dataObject->getGenerator());

            $this->output->writeln("<info>Resume</info>");
            $this->output->writeln('<info>Metadata : ' . $dataObject->getEntity(), '*</info>');
            $this->output->writeln('<info>Directory : ' . $dataObject->getModule(), '*</info>');
            $this->output->writeln("<info>Generator : " . $crudGenerator->getDefinition(), "*</info>");

            $doI = $this->dialog->askConfirmation(
                $this->output,
                "\n<question>Do you confirm generation (may others question generator ask you) ?</question> "
            );

            if ($doI === true) {
                $dataObject = $crudGenerator->generate($dataObject);
            } else {
                throw new \RuntimeException('Command aborted');
            }
        }
    }
}
