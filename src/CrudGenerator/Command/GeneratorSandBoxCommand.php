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
use CrudGenerator\Utils\FileManagerStub;
use CrudGenerator\MetaData\Config\MetaDataConfigReaderFactory;
use CrudGenerator\MetaData\MetaDataSourceFinderFactory;
use CrudGenerator\Generators\GeneratorFinderFactory;

use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Utils\DiffPHP;
use Symfony\Component\Console\Helper\DialogHelper;

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
class GeneratorSandBoxCommand extends Command
{
    /**
     * @param string $name
     */
    public function __construct(
        $name = null
    ) {
        parent::__construct($name);
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('CodeGenerator:generator-sand-box')
             ->setDescription('Allow you to test generator with fake metadata');
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
        $generator  = $this->generatorQuestion($output, $dialog);

        $view              = ViewFactory::getInstance();
        $fileManager       = new FileManagerStub($dialog, $output);
        $generiqueQuestion = new GeneriqueQuestions($dialog, $output, $fileManager);
        $diffPHP           = new DiffPHP();

        $DTOName        = $generator->getDTO();
        $generatorClass = get_class($generator);

        $generator = new $generatorClass($view, $output, $fileManager, $dialog, $generiqueQuestion, $diffPHP);

        $metadata = new MetadataDataObjectDoctrine2(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );

        $column = new MetaDataColumnDataObject();
        $column->setName('id')
               ->setNullable(true)
               ->setType('integer')
               ->setLength('100');

        $metadata->addIdentifier('id');
        $metadata->setName('toto');

        $column = new MetaDataColumnDataObject();
        $column->setName('tetze')
               ->setNullable(true)
               ->setType('integer')
               ->setLength('100');

        $column = new MetaDataColumnDataObject();
        $column->setName('myDate')
        ->setNullable(true)
        ->setType('date')
        ->setLength('100');

        $metadata->appendColumn($column);


        $dataObject = new $DTOName();
        $dataObject->setEntity($metadata->getName())
                   ->setModule('data')
                   ->setMetaData($metadata);

        $dataObject = $generator->generate($dataObject);
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

        $codeGenerator = new CodeGeneratorFactory();

        foreach ($generatorCollection as $generatorClassName) {
            $generator = $codeGenerator->create($output, $dialog, $generatorClassName);
            $generatorsChoices[$generator->getDefinition()] = $generator;
        }

        $generatorKeysChoices = array_keys($generatorsChoices);

        $choice = $dialog->select($output, "Choose a generators \n> ", $generatorKeysChoices, 0);

        return $generatorsChoices[$generatorKeysChoices[$choice]];
    }
}
