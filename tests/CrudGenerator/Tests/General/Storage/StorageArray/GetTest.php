<?php
namespace CrudGenerator\Tests\General\Storage\StorageArray;

use CrudGenerator\Storage\StorageArray;

class GetTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageArray();

        $this->assertEquals(array(), $sUT->get(array()));

        $value = 'im a value !';
        $key   = 'im a key !';

        $sUT->set(array($key, $value));

        $this->assertEquals(array($key => $value), $sUT->get(array()));

        $this->assertEquals($value, $sUT->get(array($key)));

        $this->assertEquals(null, $sUT->get(array('unknow')));
    }
}
