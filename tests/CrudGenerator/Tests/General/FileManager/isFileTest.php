<?php
namespace CrudGenerator\Tests\General\FileManager;

use CrudGenerator\FileManager;

class isFile extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $sUT = new FileManager();

        $this->assertEquals(
            true,
            $sUT->isFile(__FILE__)
        );
    }
}

