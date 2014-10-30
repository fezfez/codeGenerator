<?php
namespace CrudGenerator\Tests\General\Utils\Installer;

use CrudGenerator\Utils\Installer;
use CrudGenerator\Tests\TestCase;

class InstallTest extends TestCase
{
    public function testThrowExpection()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');

        $fileManagerExpectsCreateDir = $fileManager->expects($this->once());
        $fileManagerExpectsCreateDir->method('ifDirDoesNotExistCreate');

        $fileManagerExpectsIsWritable = $fileManager->expects($this->once());
        $fileManagerExpectsIsWritable->method('isWritable');
        $fileManagerExpectsIsWritable->will($this->returnValue(false));

        $this->setExpectedException('RuntimeException');

        Installer::install($fileManager);
    }

    public function testOk()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');

        $fileManagerExpectsCreateDir = $fileManager->expects($this->exactly(4));
        $fileManagerExpectsCreateDir->method('ifDirDoesNotExistCreate');

        $fileManagerExpectsIsWritable = $fileManager->expects($this->exactly(4));
        $fileManagerExpectsIsWritable->method('isWritable');

        Installer::install($fileManager);
    }
}
