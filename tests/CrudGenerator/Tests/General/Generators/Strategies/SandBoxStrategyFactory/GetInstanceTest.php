<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\SandBoxStrategyFactory;

use CrudGenerator\Generators\Strategies\SandBoxStrategyFactory;
use CrudGenerator\Context\CliContext;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;

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

    public function testInstanceWithFilter()
    {
        $output =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $output);

        $argument = new InputArgument('filter');

        $definition = new InputDefinition(array($argument));
        $input = new ArrayInput(array('filter' => 'toto'), $definition);

        $this->assertInstanceOf(
        	'CrudGenerator\Generators\Strategies\SandBoxStrategy',
        	SandBoxStrategyFactory::getInstance($context, $input)
		);
    }
}