<?php
namespace CrudGenerator\Tests\General\Storage\StorageString;

use CrudGenerator\Storage\StorageString;

class GetTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageString();

        $this->assertEquals(null, $sUT->get(array()));
    }
}
