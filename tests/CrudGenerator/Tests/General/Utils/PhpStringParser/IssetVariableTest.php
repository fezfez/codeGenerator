<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParserFactory;

class IssetVariableTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = PhpStringParserFactory::getInstance();

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
        $sUT = PhpStringParserFactory::getInstance(array('test' => 'myVal'));

        $this->assertEquals(
            true,
            $sUT->issetVariable('test')
        );
    }
}
