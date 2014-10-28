<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class FileGetContentTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $filePath    = __DIR__ . '/test.phtml';
        $fileContent = 'toto';

        $sUT = new FileManager();

        file_put_contents($filePath, $fileContent);

        $this->assertEquals(
            $sUT->fileGetContent($filePath),
            $fileContent
        );

        unlink($filePath);
    }

    public function testFail()
    {
        $sUT = new FileManager();

        $this->setExpectedException('RuntimeException');

        $sUT->fileGetContent('I do not exist');
    }
}
