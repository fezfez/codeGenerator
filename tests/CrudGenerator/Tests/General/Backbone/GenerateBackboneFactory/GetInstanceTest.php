<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateBackboneFactory;

use CrudGenerator\Backbone\GenerateBackboneFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\GenerateBackbone',
            GenerateBackboneFactory::getInstance($contextStub)
        );
    }
}
