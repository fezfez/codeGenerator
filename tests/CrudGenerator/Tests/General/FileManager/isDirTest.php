<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\FileManager;

class IsDirTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $sUT = new FileManager();

        $this->assertEquals(
            true,
            $sUT->isDir(__DIR__)
        );
    }
}
