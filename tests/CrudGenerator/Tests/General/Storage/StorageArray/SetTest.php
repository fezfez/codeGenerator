<?php
namespace CrudGenerator\Tests\General\Storage\StorageArray;

use CrudGenerator\Storage\StorageArray;

class SetTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageArray();

        $sUT->set(array('toto', 'tutu', ''));
    }
}
