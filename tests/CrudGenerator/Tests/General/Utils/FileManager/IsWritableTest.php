<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\Utils\FileManager;

class IsWritableTest extends \PHPUnit_Framework_TestCase
{
    public function testUnkownPAth()
    {
        $sUT = new FileManager();

        $this->assertFalse($sUT->isWritable('a path that does no exist'));
    }
}
