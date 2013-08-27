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
use CrudGenerator\MetaData\Config\MetaDataConfigReader;
use CrudGenerator\Adapter\AdapterFinderFactory;
use CrudGenerator\Generators\GeneratorFinderFactory;
use CrudGenerator\History\HistoryFactory;
use CrudGenerator\History\HistoryManager;
use CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

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
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var CodeGeneratorFactory
     */
    private $codeGeneratorFactory = null;
    /**
     * @var MetadataConfigReader
     */
    private $metadataConfigReader = null;

    /**
     * @param string $name
     * @param HistoryManager $historyManager
     * @param CodeGeneratorFactory $codeGeneratorFactory
     * @param MetadataConfigReader $metadataConfigReader
     */
    public function __construct(
        $name = null,
        HistoryManager $historyManager = null,
        CodeGeneratorFactory $codeGeneratorFactory = null,
        MetaDataConfigReader $metadataConfigReader = null
    ) {
        $this->historyManager = (null === $historyManager) ? HistoryFactory::getInstance() : $historyManager;
        $this->codeGeneratorFactory = (null === $codeGeneratorFactory) ?
                                        new CodeGeneratorFactory() : $codeGeneratorFactory;
        $this->metadataConfigReader = $metadataConfigReader;
        parent::__construct($name);
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
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
        $dialog              = $this->getHelperSet()->get('dialog');

        $adapter             = $this->adapterQuestion($output, $dialog);
        $adapterConfig       = $adapter->getConfig();

        if(null === $this->metadataConfigReader) {
            $this->metadataConfigReader = MetaDataConfigReaderFactory::getInstance($output, $dialog);
        }

        $adapterFactory      = $adapter->getFactory();

        if (null !== $adapterConfig) {
            $adapterConfig = $this->metadataConfigReader->config($adapterConfig);
            $adapterDAO    = $adapterFactory::getInstance($adapterConfig);
        } else {
            $adapterDAO = $adapterFactory::getInstance();
        }

        $entity     = $this->entityQuestion($output, $dialog, $adapterDAO->getAllMetadata());
        $moduleName = $this->moduleQuestion($output, $dialog);
        $generator  = $this->generatorQuestion($output, $dialog);

        $dataObject = new \CrudGenerator\Generators\ArchitectGenerator\Architect();
        $dataObject->setEntity($entity)
                   ->setModule($moduleName)
                   ->setMetaData($adapterDAO->getMetadataFor($entity))
                   ->setGenerator($generator);

        $crudGenerator = $this->codeGeneratorFactory->create($output, $dialog, $generator);

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

            $this->historyManager->create($dataObject);

            return true;
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
    private function adapterQuestion(OutputInterface $output, DialogHelper $dialog)
    {
        $adapterFinder      = AdapterFinderFactory::getInstance();
        $adaptersCollection = $adapterFinder->getAllAdapters();
        $output->writeln('<question>Adapters list</question>');

        foreach ($adaptersCollection as $adapter) {
            $falseDependencies = $adapter->getFalseDependencies();

            if (!empty($falseDependencies)) {
                $output->writeln(
                    '<error>Dependencies not complet for use adapter "' . $adapter->getName() . '" caused by</error>'
                );
                $output->writeln('<error> * ' . $falseDependencies . '</error>');
            } else {
                $output->writeln('<comment>' . $adapter->getDefinition() . '</comment>');
                $adaptersChoices[$adapter->getName()] = $adapter;
            }
        }

        $adaptersKeysChoices = array_keys($adaptersChoices);
        $choice = $dialog->select(
            $output,
            "Choose an adapter \n> ",
            $adaptersKeysChoices,
            0
        );

        return $adaptersChoices[$adaptersKeysChoices[$choice]];
    }

    /**
     * Ask in wich module you want to write
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @throws \InvalidArgumentException
     * @return string
     */
    private function moduleQuestion(OutputInterface $output, DialogHelper $dialog)
    {
        $output->writeln('<question>Directory list</question>');

        try {
            ZendFramework2Environnement::getDependence(new FileManager());
            $modulesChoices = glob('module/*');
        } catch (EnvironnementResolverException $e) {
            $modulesChoices = glob('*');
        }

        foreach ($modulesChoices as $moduleName) {
            $output->writeln('<comment>  ' . $moduleName . '</comment>');
        }

        return $dialog->select(
            $output,
            "Choose a target directory \n> ",
            $modulesChoices,
            0
        );
    }

    /**
     * Ask wich generator you want to use
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @throws \InvalidArgumentException
     * @return string
     */
    private function generatorQuestion(OutputInterface $output, DialogHelper $dialog)
    {
        $crudFinder = GeneratorFinderFactory::getInstance();
        $generators = $crudFinder->getAllClasses();

        $output->writeln('<question>Chose a generator</question>');
        foreach ($generators as $number => $generatorClassName) {
            $generator = $this->codeGeneratorFactory->create($output, $dialog, $generatorClassName);
            $output->writeln('<comment>' . $number . '. ' . $generator->getDefinition() . '</comment>');
        }

        return $dialog->select($output, "Choose a generators \n> ", array_keys($generators), 0);
    }

    /**
     * Ask wich entity you want to use
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param MetaDataDataObjectCollection $allMetaData
     * @throws InvalidArgumentException
     * @return string
     */
    private function entityQuestion(
        OutputInterface $output,
        DialogHelper $dialog,
        MetaDataDataObjectCollection $allMetaData
    ) {
        $output->writeln('<question>Entities list</question>');
        $entityChoices = array();
        foreach ($allMetaData as $class) {
            $output->writeln('<comment>  ' . $class->getName() . '</comment>');
            $entityChoices[] = $class->getName();
        }

        $choice = $dialog->select(
            $output,
            "Full namespace Entity \n> ",
            $entityChoices,
            0
        );

        return $entityChoices[$choice];
    }
}
