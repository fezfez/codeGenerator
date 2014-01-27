<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFactory;

use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\GeneratorCli',
            GeneratorFactory::getInstance($context, $stategy)
        );
    }

    public function testInstanceWeb()
    {
    	$web =  $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($web);

    	$stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
    	->disableOriginalConstructor()
    	->getMock();

    	$this->assertInstanceOf(
    		'CrudGenerator\Generators\GeneratorWeb',
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