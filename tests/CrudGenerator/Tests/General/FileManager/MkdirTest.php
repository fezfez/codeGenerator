<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\FileManager;

class MkdirTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $filePath = __DIR__ . '/foo';

        $sUT = new FileManager();
        $sUT->mkdir($filePath);

        $this->assertEquals(
            true,
            file_exists($filePath)
        );

        rmdir($filePath);
    }

    public function testFail()
    {
        $filePath = __DIR__ . '/foo/bar';

        $sUT = new FileManager();

        $this->setExpectedException('RuntimeException');

        $sUT->mkdir($filePath);
    }
}

