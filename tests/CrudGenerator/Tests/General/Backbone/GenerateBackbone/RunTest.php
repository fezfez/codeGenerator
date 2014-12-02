<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateBackbone;

use CrudGenerator\Backbone\GenerateBackbone;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Tests\TestCase;

class RunTest extends TestCase
{
    public function testInstance()
    {
        $generatorDto = new GeneratorDataObject();

        $generator = $this->createMock('CrudGenerator\Generators\Generator');

        $generatorExpects = $generator->expects($this->once());
        $generatorExpects->method('generate');
        $generatorExpects->with($generatorDto);

        $sUT = new GenerateBackbone($generator);

        $sUT->run($generatorDto);
    }
}
