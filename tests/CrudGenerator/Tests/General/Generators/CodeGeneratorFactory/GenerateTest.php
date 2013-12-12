<?php
namespace CrudGenerator\Tests\General\Generators\CodeGeneratorFactory;

use CrudGenerator\Generators\CodeGeneratorFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorOnEmptyMetaData()
    {
        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $codeGenerator = new CodeGeneratorFactory($strategy);
        $sUT = $codeGenerator->create(
        	$stubOutput,
        	new DialogHelper(),
        	'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
        );

        $this->setExpectedException('RuntimeException');

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity');

        $sUT->generate($dataObject);
    }

    public function testErrorOnEmptyIdentifier()
    {
        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $codeGenerator = new CodeGeneratorFactory($strategy);
        $sUT = $codeGenerator->create(
        	$stubOutput,
        	new DialogHelper(),
        	'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
        );

        $this->setExpectedException('RuntimeException');

        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $column = new MetaDataColumn();
        $column->setName('toto');
        $metadata->appendColumn($column);

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
        ->setMetadata($metadata);

        $sUT->generate($dataObject);
    }

    public function testErrorOnIdentifierMoreThanZero()
    {
    	$stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();
    	$strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
    	->disableOriginalConstructor()
    	->getMock();

    	$codeGenerator = new CodeGeneratorFactory($strategy);
    	$sUT = $codeGenerator->create(
    			$stubOutput,
    			new DialogHelper(),
    			'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
    	);

    	$this->setExpectedException('RuntimeException');

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

    	$sUT->generate($dataObject);
    }

    public function testErrorOnIdentifierNotCallId()
    {
    	$stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();
    	$strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
    	->disableOriginalConstructor()
    	->getMock();

    	$codeGenerator = new CodeGeneratorFactory($strategy);
    	$sUT = $codeGenerator->create(
    			$stubOutput,
    			new DialogHelper(),
    			'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
    	);

    	$this->setExpectedException('RuntimeException');

    	$metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
    	$column = new MetaDataColumn();
    	$column->setName('toto')
    	->setPrimaryKey(true);
    	$metadata->appendColumn($column);

    	$dataObject = new Architect();
    	$dataObject->setEntity('TestZf2\Entities\NewsEntity')
    	->setMetadata($metadata);

    	$sUT->generate($dataObject);
    }

    public function testOk()
    {
    	$stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();
    	$strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\SandBoxStrategy')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubDialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
    	->disableOriginalConstructor()
    	->getMock();

    	$codeGenerator = new CodeGeneratorFactory($strategy);
    	$sUT = $codeGenerator->create(
    		$stubOutput,
    		$stubDialog,
    		'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
    	);

    	$metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
    	$column = new MetaDataColumn();
    	$column->setName('id')
    	->setPrimaryKey(true);
    	$metadata->appendColumn($column);

    	$dataObject = new Architect();
    	$dataObject->setEntity('TestZf2\Entities\NewsEntity')
    	->setMetadata($metadata);

    	$sUT->generate($dataObject);
    }
}
