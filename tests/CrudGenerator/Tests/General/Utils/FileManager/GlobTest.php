<?php
namespace CrudGenerator\Tests\General\Utils\FileManager;

use CrudGenerator\Utils\FileManager;

class GlobTest extends \PHPUnit_Framework_TestCase
{
    public function testGlob()
    {
        $dirPath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'File'.DIRECTORY_SEPARATOR;
        $sUT     = new FileManager();
        $files   = $sUT->glob($dirPath.'*');

        $this->assertInternalType('array', $files);
        $this->assertEquals(2, count($files));
    }
}
