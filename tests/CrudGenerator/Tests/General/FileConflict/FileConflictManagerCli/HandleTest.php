<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManager;

use CrudGenerator\FileConflict\FileConflictManagerCli;

class HandleTest extends \PHPUnit_Framework_TestCase
{
   public function testShowDiff()
   {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $contextStub->expects($this->exactly(2))
        ->method('askCollection')
        ->will($this->onConsecutiveCalls(FileConflictManagerCli::SHOW_DIFF, FileConflictManagerCli::CANCEL));

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $diffPHP = $this->getMockBuilder('SebastianBergmann\Diff\Differ')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileConflictManagerCli($contextStub, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }

    public function testPostPone()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $contextStub->expects($this->once())
        ->method('askCollection')
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

        $sUT = new FileConflictManagerCli($contextStub, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }

    public function testErase()
    {
        $contextStub =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $contextStub->expects($this->once())
        ->method('askCollection')
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

        $sUT = new FileConflictManagerCli($contextStub, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }
}