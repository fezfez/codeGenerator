<?php
namespace CrudGenerator\Tests\FileManager;

use CrudGenerator\FileManager;

class filePutsContentTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $filePath = __DIR__ . '/test.phtml';
        $content  = 'toto';

        $sUT = new FileManager();

        $fileContent = $sUT->filePutsContent($filePath, $content);

        $this->assertEquals(
             $content,
             file_get_contents($filePath)
        );

        unlink($filePath);
    }
}

