<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateFileBackboneFactory;

use CrudGenerator\Backbone\GenerateFileBackboneFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\GenerateFileBackbone',
            GenerateFileBackboneFactory::getInstance($contextStub)
        );
    }
}
