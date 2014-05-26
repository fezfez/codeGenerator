<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorCli;

use CrudGenerator\Generators\GeneratorCli;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\CliContext;

class GenerateTest extends \PHPUnit_Framework_TestCase
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

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerCli')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->once())
        ->method('test')
        ->will($this->returnValue(true));

        $fileConflict->expects($this->once())
        ->method('handle')
        ->will($this->returnValue(true));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorCli($stategy, $fileConflict, $fileManager, $context);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue');

        $sUt->generate($generator);
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

    	$fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerCli')
    	->disableOriginalConstructor()
    	->getMock();

    	$fileConflict->expects($this->once())
    	->method('test')
    	->will($this->returnValue(false));

    	$fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
    	->disableOriginalConstructor()
    	->getMock();

    	$fileManager->expects($this->once())
    	->method('filePutsContent')
    	->will($this->returnValue(false));

    	$sUt = new GeneratorCli($stategy, $fileConflict, $fileManager, $context);

    	$generator = new GeneratorDataObject();

    	$generator->addFile('test', 'myName', 'MyValue');

    	$sUt->generate($generator);
    }

    public function testOnFile()
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
    	->will($this->returnValue('myGenerateFile'));

    	$fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerCli')
    	->disableOriginalConstructor()
    	->getMock();

    	$fileConflict->expects($this->never())
    	->method('test');

    	$fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUt = new GeneratorCli($stategy, $fileConflict, $fileManager, $context);

    	$generator = new GeneratorDataObject();

    	$generator->addFile('test', 'myName', 'MyValue', 'myResult');

    	$this->assertEquals(
    		'myGenerateFile',
    		$sUt->generate($generator, 'myName')
    	);
    }

    public function testOnFileException()
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

    	$fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerCli')
    	->disableOriginalConstructor()
    	->getMock();

    	$fileConflict->expects($this->never())
    	->method('test');

    	$fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUt = new GeneratorCli($stategy, $fileConflict, $fileManager, $context);

    	$generator = new GeneratorDataObject();

		$this->setExpectedException('InvalidArgumentException');
    	$sUt->generate($generator, 'DoesNotExist');
    }
}