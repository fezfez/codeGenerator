<?php
namespace CrudGenerator\Tests\General\Generators\Generator;

use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorDataObject;

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
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

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
