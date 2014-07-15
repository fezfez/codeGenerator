<?php
namespace CrudGenerator\Tests\General\Backbone\GenerateFileBackboneFactory;

use CrudGenerator\Backbone\GenerateFileBackboneFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\GenerateFileBackbone',
            GenerateFileBackboneFactory::getInstance($contextStub)
        );
    }
}
