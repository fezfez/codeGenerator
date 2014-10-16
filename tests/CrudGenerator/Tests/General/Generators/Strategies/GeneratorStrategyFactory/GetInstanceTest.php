<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategyFactory;

use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Strategies\GeneratorStrategy',
            GeneratorStrategyFactory::getInstance($context)
        );
    }
}
