<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateFileBackbone;

use CrudGenerator\Backbone\GenerateFileBackbone;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Tests\TestCase;

class RunTest extends TestCase
{
    public function testCorrectlyCall()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');
        $generator   = $this->createMock('CrudGenerator\Generators\Generator');

        $dto = new GeneratorDataObject();
        $dto->addFile('skeletonPath', 'myName', 'myValue');

        $contextExpects = $contextStub->expects($this->once());
        $contextExpects->method('askCollection');
        $contextExpects->willReturn('myName');

        $generatorExpects = $generator->expects($this->once());
        $generatorExpects->method('generateFile');
        $generatorExpects->with($dto, 'myName');

        $sUT = new GenerateFileBackbone($generator, $contextStub);

        $sUT->run($dto);
    }
}
