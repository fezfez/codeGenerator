<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParser;

class IssetVariableTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = new PhpStringParser();

        $this->assertEquals(
            false,
            $sUT->issetVariable('test')
        );

        $sUT->addVariable('test', 'myValue');

        $this->assertEquals(
            true,
            $sUT->issetVariable('test')
        );
    }

    public function testWithPredefine()
    {
        $sUT = new PhpStringParser(array('test' => 'myValue'));

        $this->assertEquals(
            true,
            $sUT->issetVariable('test')
        );
    }
}
