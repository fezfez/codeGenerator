<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManager;

use CrudGenerator\FileConflict\FileConflictManagerCli;

class HandleTest extends \PHPUnit_Framework_TestCase
{
   public function testShowDiff()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog->expects($this->exactly(2))
        ->method('select')
        ->will($this->onConsecutiveCalls(FileConflictManagerCli::SHOW_DIFF, FileConflictManagerCli::CANCEL));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $diffPHP = $this->getMockBuilder('SebastianBergmann\Diff\Differ')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileConflictManagerCli($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }

    public function testPostPone()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog->expects($this->once())
        ->method('select')
        ->will($this->returnValue(FileConflictManagerCli::POSTPONE));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->exactly(1))
        ->method('filePutsContent')
        ->will($this->returnValue('test'));


        $diffPHP = $this->getMockBuilder('SebastianBergmann\Diff\Differ')
        ->disableOriginalConstructor()
        ->getMock();
        $diffPHP->expects($this->once())
        ->method('diff')
        ->will($this->returnValue('test'));

        $sUT = new FileConflictManagerCli($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }

    public function testErase()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog->expects($this->once())
        ->method('select')
        ->will($this->returnValue(FileConflictManagerCli::ERASE));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->once())
        ->method('filePutsContent')
        ->will($this->returnValue('test'));


        $diffPHP = $this->getMockBuilder('SebastianBergmann\Diff\Differ')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileConflictManagerCli($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }
}