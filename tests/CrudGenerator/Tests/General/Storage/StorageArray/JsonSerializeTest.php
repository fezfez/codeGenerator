<?php
namespace CrudGenerator\Tests\General\Storage\StorageArray;

use CrudGenerator\Storage\StorageArray;

class JsonSerializeTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageArray();

        $value = 'test';
        $key = 'testkey';
        $sUT->set(array($key, $value));

        $this->assertEquals('{"'.$key.'":"'.$value.'"}', json_encode($sUT));
    }
}
