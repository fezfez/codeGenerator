<?php
namespace CrudGenerator\Tests\General\Backbone\MainBackboneFactory;

use CrudGenerator\Backbone\MainBackboneFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\MainBackbone',
            MainBackboneFactory::getInstance($contextStub)
        );
    }
}
