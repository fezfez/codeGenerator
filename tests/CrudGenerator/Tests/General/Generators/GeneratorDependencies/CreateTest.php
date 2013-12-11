<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorDependencies;

use CrudGenerator\Generators\GeneratorDependencies;
use CrudGenerator\Generators\CrudGenerator\Crud;
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\History\History;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
    	$stubHistory = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubCreateCommand =  $this->getMockBuilder('CrudGenerator\Command\CreateCommand')
    	->disableOriginalConstructor()
    	->getMock();

    	$stubCreateCommand->expects($this->once())
    	->method('create')
    	->will($this->returnArgument(0));

    	$dto = new Architect();
    	$dto->setEntity('TestZf2\Entities\NewsEntity')
    	->setMetadata($this->getMetadata())
    	->setModule('module/MyModule/');

    	$sUT = new GeneratorDependencies($stubHistory, $stubCreateCommand);

    	$sUT->create($dto, 'CrudGenerator\Generators\ArchitectGenerator\Architect');
    }

    private function getMetadata()
    {
    	return include __DIR__ . '/../FakeMetaData.php';
    }
}
