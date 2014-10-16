<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\SandBoxStrategyFactory;

use CrudGenerator\Generators\Strategies\SandBoxStrategyFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Strategies\SandBoxStrategy',
            SandBoxStrategyFactory::getInstance($context)
        );
    }
}
