<?php
namespace CrudGenerator\Tests\General\Backbone\MainBackboneFactory;

use CrudGenerator\Backbone\MainBackboneFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\MainBackbone',
            MainBackboneFactory::getInstance($contextStub)
        );
    }
}
