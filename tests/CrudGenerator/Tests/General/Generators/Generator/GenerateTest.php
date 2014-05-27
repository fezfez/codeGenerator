<?php
namespace CrudGenerator\Tests\General\Generators\Generator;

use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\CliContext;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

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
        $generator->setDTO(new Architect());

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
        $generator->setDTO(new Architect());

        $generator->addFile('test', 'myName', 'MyValue');

        $sUt->generate($generator);
    }
}