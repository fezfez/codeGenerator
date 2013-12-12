<?php
namespace CrudGenerator\Tests\General\Generators\CodeGeneratorFactory;

use CrudGenerator\Generators\CodeGeneratorFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

class GetDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
    	$stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();
    	$strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\SandBoxStrategy')
    	->disableOriginalConstructor()
    	->getMock();
    	$dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
    	->disableOriginalConstructor()
    	->getMock();

    	$codeGenerator = new CodeGeneratorFactory($strategy);
    	$codeGenerator->create(
    		$stubOutput,
    		$dialog,
    		'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
    	);

    	$this->assertInternalType('string', $codeGenerator->getDefinition());
    }
}
