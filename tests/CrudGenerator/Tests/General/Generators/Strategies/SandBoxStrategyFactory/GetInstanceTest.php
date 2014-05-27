<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\SandBoxStrategyFactory;

use CrudGenerator\Generators\Strategies\SandBoxStrategyFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

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
        $app =  $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();
        $context = new WebContext($app);

        $this->setExpectedException('InvalidArgumentException');
        SandBoxStrategyFactory::getInstance($context);
    }
}