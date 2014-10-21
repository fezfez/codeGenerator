<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateFileBackbone;

use CrudGenerator\Backbone\GenerateFileBackbone;
use CrudGenerator\Generators\GeneratorDataObject;

class RunTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectlyCall()
    {
        $contextStub = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $generator   = $this->getMockBuilder('CrudGenerator\Generators\Generator')
        ->disableOriginalConstructor()
        ->getMock();

        $dto = new GeneratorDataObject();
        $dto->addFile('skeletonPath', 'myName', 'myValue');

        $contextStub->expects($this->once())
        ->method('askCollection')
        ->willReturn('myName');

        $generator->expects($this->once())
        ->method('generateFile')
        ->with($dto, 'myName');

        $sUT = new GenerateFileBackbone($generator, $contextStub);

        $sUT->run($dto);
    }
}
