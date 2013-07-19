<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\FileManager;

class UnlinkTest extends \PHPUnit_Framework_TestCase
{
    public function testUnlink()
    {
        $filePath = __DIR__ . '/foo';

        touch($filePath);

        $this->assertEquals(
            true,
            file_exists($filePath)
        );

        $sUT = new FileManager();
        $sUT->unlink($filePath);

        $this->assertEquals(
            true,
            !file_exists($filePath)
        );
    }
}
