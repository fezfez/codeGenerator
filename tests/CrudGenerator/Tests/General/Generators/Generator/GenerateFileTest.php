<?php
namespace CrudGenerator\Tests\General\Generators\Generator;

use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\CliContext;

class GenerateFileTest extends \PHPUnit_Framework_TestCase
{
    public function testOnFile()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context->expects($this->once())
        ->method('log')
        ->with($this->equalTo('myGenerateFile'), $this->equalTo('previewfile'));

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy->expects($this->once())
        ->method('generateFile')
        ->will($this->returnValue('myGenerateFile'));

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->never())
        ->method('test');

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $historyManager = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new Generator($stategy, $fileConflict, $fileManager, $historyManager, $context);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue', 'myResult');

        $sUt->generateFile($generator, 'myName');
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

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->never())
        ->method('test');

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $historyManager = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new Generator($stategy, $fileConflict, $fileManager, $historyManager, $context);

        $generator = new GeneratorDataObject();

        $this->setExpectedException('InvalidArgumentException');
        $sUt->generateFile($generator, 'DoesNotExist');
    }
}
