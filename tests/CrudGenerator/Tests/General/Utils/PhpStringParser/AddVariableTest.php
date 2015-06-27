<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParserFactory;

class AddVariableTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = PhpStringParserFactory::getInstance();

        $sUT->addVariable('myVariable', 'myValue');

        $this->assertEquals(true, $sUT->issetVariable('myVariable'));
    }
}
