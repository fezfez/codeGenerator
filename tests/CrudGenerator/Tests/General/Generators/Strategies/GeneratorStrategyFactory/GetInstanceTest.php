<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategyFactory;

use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;


class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $output =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $output);

        $this->assertInstanceOf(
        	'CrudGenerator\Generators\Strategies\GeneratorStrategy',
        	GeneratorStrategyFactory::getInstance($context)
		);
    }

    public function testInstanceWeb()
    {
    	$app =  $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();
    	$context = new WebContext($app);

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