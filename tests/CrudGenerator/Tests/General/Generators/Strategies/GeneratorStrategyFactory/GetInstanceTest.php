<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategyFactory;

use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Strategies\GeneratorStrategy',
            GeneratorStrategyFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();
        $this->assertInstanceOf(
            'CrudGenerator\Generators\Strategies\GeneratorStrategy',
            GeneratorStrategyFactory::getInstance($context)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        GeneratorStrategyFactory::getInstance($context);
    }
}
