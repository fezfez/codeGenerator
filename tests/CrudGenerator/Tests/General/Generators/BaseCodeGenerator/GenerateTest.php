<?php
namespace CrudGenerator\Tests\General\Generators\BaseCodeGenerator;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Utils\DiffPHP;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;

use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testFailOnMetadatadzdaz()
    {
        $sUT      = $this->getClass();
        $metadata = $this->getMetadata();

        $dataObject = new \CrudGenerator\Generators\ArchitectGenerator\Architect;
        $dataObject->setEntity('TestZf2\Entities\NewsEntity');

        $this->setExpectedException('RuntimeException');

        $sUT->doGenerate($dataObject);
    }

    public function testFailOnIndentifier()
    {
        $sUT      = $this->getClass();

        $dataObject = new \CrudGenerator\Generators\ArchitectGenerator\Architect;
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
                   ->setMetadata(new MetadataDataObjectDoctrine2(
                new MetaDataColumnCollection(),
                new MetaDataRelationCollection()
            )
        );

        $this->setExpectedException('RuntimeException');

        $sUT->doGenerate($dataObject);
    }

    public function testFailOnNameIndentifierMultipe()
    {
        $sUT      = $this->getClass();
        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $column = new MetaDataColumn();
        $column->setName('toto')
        ->setPrimaryKey(true);
        $metadata->appendColumn($column);
        $column = new MetaDataColumn();
        $column->setName('titi')
        ->setPrimaryKey(true);
        $metadata->appendColumn($column);

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
        ->setMetadata($metadata);

        $this->setExpectedException('RuntimeException');

        $sUT->doGenerate($dataObject);
    }

    public function testFailOnNameIndentifier()
    {
        $sUT      = $this->getClass();
        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $column = new MetaDataColumn();
        $column->setName('toto')
               ->setPrimaryKey(true);
        $metadata->appendColumn($column);

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
                   ->setMetadata($metadata);

        $this->setExpectedException('RuntimeException');

        $sUT->doGenerate($dataObject);
    }

    /**
     * @return \CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator
     */
    private function getClass()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
                   ->method('askAndValidate')
                   ->will($this->returnValue(__DIR__));

        $stubDialog->expects($this->any())
                   ->method('ask')
                   ->will($this->returnValue('y'));

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubOutput->expects($this->any())
                   ->method('writeln')
                   ->will($this->returnValue(''));

        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, new FileManager());
        $strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();
        $stubGeneratorDependencies =  $this->getMockBuilder('CrudGenerator\Generators\GeneratorDependencies')
        ->disableOriginalConstructor()
        ->getMock();
        $stubMetaDataToArray =  $this->getMockBuilder('CrudGenerator\Generators\ArchitectGenerator\MetaDataToArray')
        ->disableOriginalConstructor()
        ->getMock();
        $stubMetaDataToArray->expects($this->any())
        ->method('ask')
        ->will($this->returnArgument(0));

        return new ArchitectGenerator(
            $stubOutput,
            $stubDialog,
            $generiqueQuestion,
            $strategy,
            $stubGeneratorDependencies,
        	$stubMetaDataToArray
        );
    }

    /**
     * @return \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2
     */
    private function getMetadata()
    {
        return include __DIR__ . '/../FakeMetaData.php';
    }
}
