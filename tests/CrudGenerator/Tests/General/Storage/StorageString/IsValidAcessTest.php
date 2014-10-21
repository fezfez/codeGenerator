<?php
namespace CrudGenerator\Tests\General\Storage\StorageString;

use CrudGenerator\Storage\StorageString;

class IsValidAcessTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageString();

        $this->assertFalse($sUT->isValidAcces(array('', '', '')));
        $this->assertFalse($sUT->isValidAcces(array('')));

        $this->assertTrue($sUT->isValidAcces(array()));
    }
}
