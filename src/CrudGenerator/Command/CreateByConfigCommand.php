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

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Generators\Questions\Cli\DirectoryQuestion;
use CrudGenerator\Generators\Questions\Cli\GeneratorQuestion;
use CrudGenerator\ConfigManager\ConfigGenerator\ManagerFactory;
use CrudGenerator\ConfigManager\ConfigMetadata\ManagerFactory as ConfigMetadataManagerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Generator command
 *
 * @author Stéphane Demonchaux
 */
class CreateByConfigCommand extends Command
{
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var DirectoryQuestion
     */
    private $directoryQuestion = null;
    /**
     * @var GeneratorQuestion
     */
    private $generatorQuestion = null;
    /**
     * @var ManagerFactory
     */
    private $managerFactory = null;
    /**
     * @var ConfigMetadataManagerFactory
     */
    private $configMetadataManagerFactory = null;

    /**
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     * @param HistoryManager $historyManager
     * @param DirectoryQuestion $directoryQuestion
     * @param GeneratorQuestion $generatorQuestion
     * @param ManagerFactory $managerFactory
     * @param ConfigMetadataManagerFactory $configMetadataManagerFactory
     */
    public function __construct(
        DialogHelper $dialog,
        OutputInterface $output,
        HistoryManager $historyManager,
        DirectoryQuestion $directoryQuestion,
        GeneratorQuestion $generatorQuestion,
        ManagerFactory $managerFactory,
        ConfigMetadataManagerFactory $configMetadataManagerFactory
    ) {
        parent::__construct('create');
        $this->dialog                       = $dialog;
        $this->output                       = $output;
        $this->historyManager               = $historyManager;
        $this->directoryQuestion            = $directoryQuestion;
        $this->generatorQuestion            = $generatorQuestion;
        $this->managerFactory               = $managerFactory;
        $this->configMetadataManagerFactory = $configMetadataManagerFactory;
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this->setName('CodeGenerator:create-by-config')
             ->setDescription('Generate code based on config');
    }

    /* (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pathToConf  = $this->directoryQuestion->ask();
        $generalInformation = $this->managerFactory->create($pathToConf)->getConfig();

        $filePath  = $this->directoryQuestion->ask(true);
        $generations = $this->configMetadataManagerFactory->create($filePath);

        $generationInformation = $generations->getMetadatas();
        $generators            = $generationInformation->getGenerators();


        foreach ($generators as $generatorName) {
            $generator  = $this->generatorQuestion->ask($generatorName);
            $DTOName    = $generator->getDTO();

            // @TODO add metadatasource

            /* @var $dataObject \CrudGenerator\DataObject */
            $dataObject = new $DTOName();
            $dataObject->setEntity($generationInformation->getMetaData()->getName())
                       ->setModule($generalInformation->getPathToModels())
                       ->setMetaData($generationInformation->getMetaData());

            $dataObject = $generations->writeAbstractOptions($generatorName, $dataObject);

            $this->output->writeln("<info>Resume</info>");
            $this->output->writeln('<info>Metadata : ' . $dataObject->getEntity(), '*</info>');
            $this->output->writeln('<info>Directory : ' . $dataObject->getModule(), '*</info>');
            $this->output->writeln("<info>Generator : " . $generator->getDefinition(), "*</info>");

            $doI = $this->dialog->askConfirmation(
                $this->output,
                "\n<question>Do you confirm generation (may others question generator ask you) ?</question> "
            );

            if (true === $doI) {
                $dataObject = $generator->generate($dataObject);
                $this->historyManager->create($dataObject);
            } else {
                throw new \RuntimeException('Command aborted');
            }
        }
    }
}
