<?php
namespace CrudGenerator\Tests\General\Storage\StorageArray;

use CrudGenerator\Storage\StorageArray;

class IsValidStoreTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageArray();

        $this->assertFalse($sUT->isValidStore(array('', '', '')));
        $this->assertFalse($sUT->isValidStore(array('')));

        $this->assertTrue($sUT->isValidStore(array('', '')));
    }
}
