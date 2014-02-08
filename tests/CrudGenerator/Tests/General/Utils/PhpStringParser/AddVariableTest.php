<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParser;

class AddVariableTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = new PhpStringParser();

        $sUT->addVariable('myVariable', 'myValue');
    }
}
