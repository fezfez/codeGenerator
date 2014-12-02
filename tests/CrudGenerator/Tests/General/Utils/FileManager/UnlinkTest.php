<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class UnlinkTest extends \PHPUnit_Framework_TestCase
{
    public function testUnlink()
    {
        $filePath = __DIR__.'/foo';

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

    public function testFail()
    {
        $filePath = __DIR__.'/foo';

        $this->assertEquals(
            false,
            file_exists($filePath)
        );

        $sUT = new FileManager();

        $this->setExpectedException('RuntimeException');
        $sUT->unlink($filePath);
    }
}
