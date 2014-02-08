<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class FilePutsContentTest extends \PHPUnit_Framework_TestCase
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

    public function testFail()
    {
        $filePath = __DIR__ . '/toto/test.phtml';
        $content  = 'toto';

        $sUT = new FileManager();

        $this->setExpectedException('RuntimeException');
        $sUT->filePutsContent($filePath, $content);
    }
}
