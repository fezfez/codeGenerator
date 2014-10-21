<?php
namespace CrudGenerator\Tests\General\Storage\StorageString;

use CrudGenerator\Storage\StorageString;

class SetTest extends \PHPUnit_Framework_TestCase
{
    public function testWithVariableNumberOfArgs()
    {
        $sUT = new StorageString();

        $sUT->set(array('toto'));
    }
}
