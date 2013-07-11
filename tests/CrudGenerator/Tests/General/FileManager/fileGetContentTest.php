<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\FileManager;

class fileGetContentTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $filePath = __DIR__ . '/test.phtml';
        $fileContent = 'toto';

        $sUT = new FileManager();

        file_put_contents($filePath, $fileContent);

        $this->assertEquals(
             $sUT->fileGetContent($filePath),
             $fileContent
        );

        unlink($filePath);
    }
}

