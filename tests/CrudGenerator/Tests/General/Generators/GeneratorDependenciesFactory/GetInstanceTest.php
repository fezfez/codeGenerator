<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorDependenciesFactory;

use CrudGenerator\Generators\GeneratorDependenciesFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
    	$stubDialog = $this->getMockBuilder('\Symfony\Component\Console\Helper\DialogHelper')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();

        $this->assertInstanceOf(
            '\CrudGenerator\Generators\GeneratorDependencies',
            GeneratorDependenciesFactory::getInstance($stubDialog, $stubOutput)
        );
    }

    public function testType2()
    {
    	$stubDialog = $this->getMockBuilder('\Symfony\Component\Console\Helper\DialogHelper')
    	->disableOriginalConstructor()
    	->getMock();
    	$stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
    	->disableOriginalConstructor()
    	->getMock();

    	$this->assertInstanceOf(
    		'\CrudGenerator\Generators\GeneratorDependencies',
    		GeneratorDependenciesFactory::getInstance($stubDialog, $stubOutput, true)
    	);
    }
}
