<?php
namespace CrudGenerator\Tests\General\Backbone\CreateSourceBackboneFactory;

use CrudGenerator\Backbone\CreateSourceBackboneFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\CreateSourceBackbone',
            CreateSourceBackboneFactory::getInstance($contextStub)
        );
    }
}
