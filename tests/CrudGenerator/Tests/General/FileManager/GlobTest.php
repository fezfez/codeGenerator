<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class GlobTest extends \PHPUnit_Framework_TestCase
{
    public function testGlob()
    {
        $filePath = __DIR__;

        $sUT = new FileManager();
        $sUT->glob($filePath);
    }
}
