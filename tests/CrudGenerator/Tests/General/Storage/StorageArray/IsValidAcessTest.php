<?php
namespace CrudGenerator\Tests\General\Storage\StorageArray;

use CrudGenerator\Storage\StorageArray;

class IsValidAcessTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageArray();

        $this->assertFalse($sUT->isValidAcces(array('', '', '')));
        $this->assertFalse($sUT->isValidAcces(array('', '')));

        $this->assertTrue($sUT->isValidAcces(array()));
        $this->assertTrue($sUT->isValidAcces(array('')));
    }
}
