<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorWeb;

use CrudGenerator\Generators\GeneratorWeb;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\CliContext;

class CheckConflictTest extends \PHPUnit_Framework_TestCase
{
    public function testConflict()
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

        $stategy->expects($this->once())
        ->method('generateFile')
        ->will($this->returnValue('myGeneratedFile'));

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->once())
        ->method('test')
        ->will($this->returnValue(true));

        $fileConflict->expects($this->once())
        ->method('handle')
        ->will($this->returnValue('MyDiff'));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue');

    	$this->assertEquals(
    		array(
    			'MyValue' => array(
    				'isConflict' => true,
					'result' => 'myGeneratedFile',
    				'diff' => 'MyDiff'
    			)
    		),
    		$sUt->checkConflict($generator)
    	);
    }

    public function testOk()
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

    	$stategy->expects($this->once())
    	->method('generateFile')
    	->will($this->returnValue('myGeneratedFile'));

    	$fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
    	->disableOriginalConstructor()
    	->getMock();

    	$fileConflict->expects($this->once())
    	->method('test')
    	->will($this->returnValue(false));

    	$fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

    	$generator = new GeneratorDataObject();

    	$generator->addFile('test', 'myName', 'MyValue');

    	$this->assertEquals(
    		array(
    			'MyValue' => array(
    				'isConflict' => false,
					'result' => 'myGeneratedFile'
    			)
    		),
    		$sUt->checkConflict($generator)
    	);
    }
}