<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\SandBoxStrategyFactory;

use CrudGenerator\Generators\Strategies\SandBoxStrategyFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $output =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $output);

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
