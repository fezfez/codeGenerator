<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFactory;

use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Generator',
            GeneratorFactory::getInstance($context, $stategy)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Generator',
            GeneratorFactory::getInstance($context, $stategy)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $this->setExpectedException('InvalidArgumentException');

        GeneratorFactory::getInstance($context, $stategy);
    }
}
