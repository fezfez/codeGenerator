<?php
namespace CrudGenerator\Tests\General\Storage\StorageString;

use CrudGenerator\Storage\StorageString;

class IsValidStoreTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageString();

        $this->assertFalse($sUT->isValidStore(array('', '', '')));
        $this->assertFalse($sUT->isValidStore(array('', '')));
        $this->assertTrue($sUT->isValidStore(array('')));
    }
}
