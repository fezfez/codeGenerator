<?php
namespace CrudGenerator\Tests\General\Generators\Questions\DirectoryQuestion;

use CrudGenerator\Generators\Questions\DirectoryQuestionFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Cli\DirectoryQuestion',
            DirectoryQuestionFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
    	$app =  $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();
    	$context = new WebContext($app);

    	$this->assertInstanceOf(
    		'CrudGenerator\Generators\Questions\Web\DirectoryQuestion',
    		DirectoryQuestionFactory::getInstance($context)
    	);
    }
}
