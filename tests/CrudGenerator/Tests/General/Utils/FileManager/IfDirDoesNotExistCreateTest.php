<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class IfDirDoesNotExistCreateTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $dirPath = __DIR__.'/foo';

        $sUT = new FileManager();

        $this->assertEquals(
            true,
            $sUT->ifDirDoesNotExistCreate($dirPath)
        );
    }

    public function testFail()
    {
        $dirPath = __DIR__.'/foo';

        $sUT = new FileManager();

        $this->assertEquals(
            false,
            $sUT->ifDirDoesNotExistCreate($dirPath)
        );

        rmdir($dirPath);
    }
}
