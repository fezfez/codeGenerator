<?php
namespace CrudGenerator\Tests\General\Storage\StorageString;

use CrudGenerator\Storage\StorageString;

class JsonSerializeTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageString();

        $value = 'test';

        $sUT->set(array($value));

        $this->assertEquals('"' . $value . '"', json_encode($sUT));
    }
}
