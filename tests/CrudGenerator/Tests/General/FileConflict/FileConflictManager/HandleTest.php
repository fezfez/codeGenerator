<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManager;

use CrudGenerator\FileConflict\FileConflictManager;
use CrudGenerator\Tests\TestCase;

class HandleTest extends TestCase
{
    public function testShowDiff()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $diffPHP     = $this->createMock('SebastianBergmann\Diff\Differ');

        $contextStubExpects = $contextStub->expects($this->exactly(2));
        $contextStubExpects->method('askCollection');
        $contextStubExpects->will($this->onConsecutiveCalls(FileConflictManager::SHOW_DIFF, FileConflictManager::CANCEL));

        $sUT = new FileConflictManager($contextStub, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }

    public function testPostPone()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $diffPHP     = $this->createMock('SebastianBergmann\Diff\Differ');

        $contextStubExpects = $contextStub->expects($this->once());
        $contextStubExpects->method('askCollection');
        $contextStubExpects->will($this->returnValue(FileConflictManager::POSTPONE));

        $fileManagerExpects = $fileManager->expects($this->exactly(1));
        $fileManagerExpects->method('filePutsContent');
        $fileManagerExpects->will($this->returnValue('test'));

        $diffPHPExpects = $diffPHP->expects($this->once());
        $diffPHPExpects->method('diff');
        $diffPHPExpects->will($this->returnValue('test'));

        $sUT = new FileConflictManager($contextStub, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }

    public function testErase()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $diffPHP     = $this->createMock('SebastianBergmann\Diff\Differ');

        $contextStubExpects = $contextStub->expects($this->once());
        $contextStubExpects->method('askCollection');
        $contextStubExpects->will($this->returnValue(FileConflictManager::ERASE));

        $fileManagerExpects = $fileManager->expects($this->once());
        $fileManagerExpects->method('filePutsContent');
        $fileManagerExpects->will($this->returnValue('test'));

        $sUT = new FileConflictManager($contextStub, $fileManager, $diffPHP);

        $sUT->handle('test', '0');
    }
}
