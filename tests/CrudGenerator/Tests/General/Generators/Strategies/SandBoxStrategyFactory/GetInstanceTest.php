<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\SandBoxStrategyFactory;

use CrudGenerator\Generators\Strategies\SandBoxStrategyFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Strategies\SandBoxStrategy',
            SandBoxStrategyFactory::getInstance($context)
        );
    }

    public function testFailInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->setExpectedException('InvalidArgumentException');
        SandBoxStrategyFactory::getInstance($context);
    }
}
