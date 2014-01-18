<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorDependencies;

use CrudGenerator\Generators\GeneratorDependencies;
use CrudGenerator\Generators\CrudGenerator\Crud;
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\History\History;

class FindDependencieTest extends \PHPUnit_Framework_TestCase
{
    public function testError()
    {
    	$stubHistory = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubCreateCommand =  $this->getMockBuilder('CrudGenerator\Command\CreateCommand')
    	->disableOriginalConstructor()
    	->getMock();

        $sUT = new GeneratorDependencies($stubHistory, $stubCreateCommand);

        $this->setExpectedException('CrudGenerator\Generators\CodeGeneratorNotFoundException');

        $sUT->findDependencie('fezfzef');
    }

    public function testNotFound()
    {
    	$stubHistory = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubCreateCommand =  $this->getMockBuilder('CrudGenerator\Command\CreateCommand')
    	->disableOriginalConstructor()
    	->getMock();


    	$dto = new Crud();
    	$dto->setEntity('TestZf2\Entities\NewsEntity')
    	->setMetadata($this->getMetadata())
    	->setModule('module/MyModule/');

    	$history = new History();
    	$history->setName('toto')
    	->addDataObject($dto);

    	$sUT = new GeneratorDependencies($stubHistory, $stubCreateCommand);

    	$this->setExpectedException('CrudGenerator\Generators\CodeGeneratorNotFoundException');

    	$sUT->findDependencie('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator');
    }

    public function testOk()
    {
    	$stubHistory = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubCreateCommand =  $this->getMockBuilder('CrudGenerator\Command\CreateCommand')
    	->disableOriginalConstructor()
    	->getMock();


    	$dto = new Architect();
    	$dto->setEntity('TestZf2\Entities\NewsEntity')
    	->setMetadata($this->getMetadata())
    	->setModule('module/MyModule/');

    	$history = new History();
    	$history->setName('toto')
    	->addDataObject($dto);

    	$stubHistory->expects($this->once())
    	->method('find')
    	->will($this->returnValue($history));

    	$sUT = new GeneratorDependencies($stubHistory, $stubCreateCommand);

    	$sUT->findDependencie('CrudGenerator\Generators\ArchitectGenerator\Architect');
    }

    private function getMetadata()
    {
    	return include __DIR__ . '/../FakeMetaData.php';
    }
}
