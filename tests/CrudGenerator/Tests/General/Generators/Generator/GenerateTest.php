<?php
namespace CrudGenerator\Tests\General\Generators\Generator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorDataObject;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testConflict()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy->expects($this->exactly(2))
        ->method('generateFile')
        ->will($this->returnValue('myGeneratedFile'));

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
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

        $historyManager = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new Generator($stategy, $fileConflict, $fileManager, $historyManager, $context);

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $generator->addFile('test', 'myName', 'MyValue');

        $sUt->generate($generator);
    }

    public function testOk()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy->expects($this->exactly(2))
        ->method('generateFile')
        ->will($this->returnValue('myGeneratedFile'));

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
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

        $historyManager = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new Generator($stategy, $fileConflict, $fileManager, $historyManager, $context);

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $generator->addFile('test', 'myName', 'MyValue');

        $sUt->generate($generator);
    }
}
