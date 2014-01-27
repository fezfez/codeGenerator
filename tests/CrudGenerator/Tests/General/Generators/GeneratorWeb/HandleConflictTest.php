<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorWeb;

use CrudGenerator\Generators\GeneratorWeb;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\CliContext;

class HandleConflictTest extends \PHPUnit_Framework_TestCase
{
    public function testConflictWithPostPone()
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

        $responseHandle = array('handle_MyValue' => 'postpone');

    	$sUt->handleConflict($generator, $responseHandle);
    }

    public function testWithErase()
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

        $fileConflict->expects($this->never())
        ->method('handle');

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue');

        $responseHandle = array('handle_MyValue' => 'erase');

    	$sUt->handleConflict($generator, $responseHandle);
    }
}