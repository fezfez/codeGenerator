<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManager;

use CrudGenerator\FileConflict\FileConflictManager;
use CrudGenerator\Tests\TestCase;

class TestTest extends TestCase
{
    public function testConflict()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $diffPHP     = $this->createMock('SebastianBergmann\Diff\Differ');

        $fileManagerExpectsIsFile = $fileManager->expects($this->exactly(2));
        $fileManagerExpectsIsFile->method('isFile');
        $fileManagerExpectsIsFile->will($this->returnValue(true));

        $fileManagerExpectsFileGetContent = $fileManager->expects($this->exactly(2));
        $fileManagerExpectsFileGetContent->method('fileGetContent');
        $fileManagerExpectsFileGetContent->will($this->returnValue('test'));

        $sUT = new FileConflictManager($contextStub, $fileManager, $diffPHP);

        $this->assertEquals(
            true,
            $sUT->test('test', '0')
        );

        $this->assertEquals(
            false,
            $sUT->test('test', 'test')
        );
    }
}
