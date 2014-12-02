<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class IncludeFileTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $file = __DIR__.'/tmpFile.php';
        $sUT  = new FileManager();

        $sUT->includeFile($file);

        $this->assertEquals(true, in_array($file, get_included_files()));
    }
}
