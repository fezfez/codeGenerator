<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateBackboneFactory;

use CrudGenerator\Backbone\GenerateBackboneFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\GenerateBackbone',
            GenerateBackboneFactory::getInstance($contextStub)
        );
    }
}
