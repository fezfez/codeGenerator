<?php
namespace CrudGenerator\Tests\General\Backbone\HistoryBackboneFactory;

use CrudGenerator\Backbone\HistoryBackboneFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\HistoryBackbone',
            HistoryBackboneFactory::getInstance($contextStub)
        );
    }
}
