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

use CrudGenerator\Adapter\AdapterDataObject;

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
        $dialog     = $this->getHelperSet()->get('dialog');

        $adapter    = $this->adapterQuestion($output, $dialog);
        $metadata   = $this->entityQuestion($output, $dialog, $adapter);
        $directory  = $this->moduleQuestion($output, $dialog);
        $generator  = $this->generatorQuestion($output, $dialog);

        $DTOName    = $generator->getDTO();

        $dataObject = new $DTOName();
        $dataObject->setEntity($metadata->getName())
                   ->setModule($directory)
                   ->setMetaData($metadata);

        $output->writeln("<info>Resume</info>");
        $output->writeln('<info>Entity : ' . $dataObject->getEntity(), '*</info>');
        $output->writeln('<info>Module : ' . $dataObject->getModule(), '*</info>');
        $output->writeln("<info>Generator : " . $generator->getDefinition(), "*</info>");

        $doI = $dialog->askConfirmation(
            $output,
            "\n<question>Do you confirm generation (may others question generator ask you) ?</question> "
        );

        if ($doI === true) {
            $dataObject = $generator->generate($dataObject);
            $this->historyManager->create($dataObject);
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

        foreach ($adaptersCollection as $adapter) {
            $falseDependencies = $adapter->getFalseDependencies();

            if (!empty($falseDependencies)) {
                $output->writeln(
                    '<error>Dependencies not complet for use adapter "' . $adapter->getName() . '" caused by</error>'
                );
                $output->writeln('<error> * ' . $falseDependencies . '</error>');
            } else {
                $adaptersChoices[$adapter->getDefinition()] = $adapter;
            }
        }

        $adaptersKeysChoices = array_keys($adaptersChoices);
        $choice = $dialog->select(
            $output,
            "<question>Choose an adapter</question> \n> ",
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
        try {
            ZendFramework2Environnement::getDependence(new FileManager());
            $modulesChoices = glob('module/*');
        } catch (EnvironnementResolverException $e) {
            $modulesChoices = glob('*');
        }

        $choice = $dialog->select(
            $output,
            "<question>Choose a target directory</question> \n> ",
            $modulesChoices,
            0
        );

        return $modulesChoices[$choice];
    }

    /**
     * Ask wich generator you want to use
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\Generators\BaseCodeGenerator
     */
    private function generatorQuestion(OutputInterface $output, DialogHelper $dialog)
    {
        $crudFinder = GeneratorFinderFactory::getInstance();
        $generatorCollection = $crudFinder->getAllClasses();

        foreach ($generatorCollection as $generatorClassName) {
            $generator = $this->codeGeneratorFactory->create($output, $dialog, $generatorClassName);
            $generatorsChoices[$generator->getDefinition()] = $generator;
        }

        $generatorKeysChoices = array_keys($generatorsChoices);

        $choice = $dialog->select($output, "Choose a generators \n> ", $generatorKeysChoices, 0);

        return $generatorsChoices[$generatorKeysChoices[$choice]];
    }

    /**
     * Ask wich metadata you want to use
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param AdapterDataObject $adapter
     * @throws InvalidArgumentException
     * @return string
     */
    private function entityQuestion(
        OutputInterface $output,
        DialogHelper $dialog,
        AdapterDataObject $adapter
    ) {

        $adapterConfig       = $adapter->getConfig();

        if(null === $this->metadataConfigReader) {
            $this->metadataConfigReader = MetaDataConfigReaderFactory::getInstance($output, $dialog);
        }

        $adapterFactory    = $adapter->getFactory();

        if (null !== $adapterConfig) {
            $adapterConfig = $this->metadataConfigReader->config($adapterConfig);
            $adapterDAO    = $adapterFactory::getInstance($adapterConfig);
        } else {
            $adapterDAO = $adapterFactory::getInstance();
        }

        $entityChoices = array();
        foreach ($adapterDAO->getAllMetadata() as $class) {
            $entityChoices[] = $class->getName();
        }

        $choice = $dialog->select(
            $output,
            "<question>Full namespace Metadata</question> \n> ",
            $entityChoices,
            0
        );

        return $adapterDAO->getMetadataFor($entityChoices[$choice]);
    }
}
