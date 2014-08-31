<?php
namespace CrudGenerator\Tests\General\Backbone\PreapreForGenerationBackboneFactory;

use CrudGenerator\Backbone\PreapreForGenerationBackboneFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\PreapreForGenerationBackbone',
            PreapreForGenerationBackboneFactory::getInstance($contextStub)
        );
    }
}
