<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManager;

use CrudGenerator\FileConflict\FileConflictManager;

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
        ->will($this->onConsecutiveCalls(FileConflictManager::SHOW_DIFF, FileConflictManager::CANCEL));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->once())
        ->method('filePutsContent')
        ->will($this->returnValue('test'));

        $fileManager->expects($this->once())
        ->method('unlink')
        ->will($this->returnValue('test'));

        $diffPHP = $this->getMockBuilder('CrudGenerator\Utils\DiffPHP')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileConflictManager($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

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
        ->will($this->returnValue(FileConflictManager::POSTPONE));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->exactly(2))
        ->method('filePutsContent')
        ->will($this->returnValue('test'));


        $diffPHP = $this->getMockBuilder('CrudGenerator\Utils\DiffPHP')
        ->disableOriginalConstructor()
        ->getMock();
        $diffPHP->expects($this->once())
        ->method('diff')
        ->will($this->returnValue('test'));

        $sUT = new FileConflictManager($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

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
        ->will($this->returnValue(FileConflictManager::ERASE));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->once())
        ->method('filePutsContent')
        ->will($this->returnValue('test'));


        $diffPHP = $this->getMockBuilder('CrudGenerator\Utils\DiffPHP')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileConflictManager($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }
}