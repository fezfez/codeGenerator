<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorWeb;

use CrudGenerator\Generators\GeneratorWeb;
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

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->once())
        ->method('test')
        ->will($this->returnValue(true));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue');

        $this->setExpectedException('CrudGenerator\Generators\GeneratorWebConflictException');
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

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->once())
        ->method('test')
        ->will($this->returnValue(false));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->once())
        ->method('isDir')
        ->with('myDirName')
        ->will($this->returnValue(false));

        $fileManager->expects($this->once())
        ->method('mkdir')
        ->with('myDirName');

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue')
                  ->addDirectories('myKey', 'myDirName');

        $sUt->generate($generator);
    }

    public function testWithPreGenerate()
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

        $stategy->expects($this->never())
        ->method('generateFile');

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->never())
        ->method('test');

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue', 'myResult');

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

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $fileConflict->expects($this->never())
        ->method('test');

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue', 'myResult');

        $this->assertEquals(
            'myGenerateFile',
            $sUt->generate($generator, 'MyValue')
        );
    }

    public function testOnFileDoesNotExist()
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

        $stategy->expects($this->never())
        ->method('generateFile');

        $fileConflict = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManagerWeb')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUt = new GeneratorWeb($stategy, $fileConflict, $fileManager);

        $generator = new GeneratorDataObject();

        $generator->addFile('test', 'myName', 'MyValue', 'myResult');

        $this->setExpectedException('InvalidArgumentException');
        $sUt->generate($generator, 'doestNotExist');
    }
}