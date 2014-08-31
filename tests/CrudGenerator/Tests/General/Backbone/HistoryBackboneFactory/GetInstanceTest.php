<?php
namespace CrudGenerator\Tests\General\Backbone\HistoryBackboneFactory;

use CrudGenerator\Backbone\HistoryBackboneFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\HistoryBackbone',
            HistoryBackboneFactory::getInstance($contextStub)
        );
    }
}
