<?php
namespace CrudGenerator\Tests\General\Generators\Parser\ParserCollectionFactory;

use CrudGenerator\Generators\Parser\ParserCollectionFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testCliInstance()
    {
    	$ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();

    	$dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new CliContext($dialog, $ConsoleOutputStub);

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\ParserCollection',
            ParserCollectionFactory::getInstance($context)
        );
    }

    public function testWebInstance()
    {
    	$app = $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($app);

    	$this->assertInstanceOf(
    		'CrudGenerator\Generators\Parser\ParserCollection',
    		ParserCollectionFactory::getInstance($context)
    	);
    }

    public function testFail()
    {
    	$context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

    	$this->setExpectedException('InvalidArgumentException');

    	ParserCollectionFactory::getInstance($context);
    }
}
