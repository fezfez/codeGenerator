<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class IncludeFileTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = new FileManager();
        $sUT->includeFile(__DIR__ . '/tmpFile.php');
    }
}
