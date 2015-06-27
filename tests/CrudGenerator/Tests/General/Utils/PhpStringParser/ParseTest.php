<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParserFactory;

class ParseTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = PhpStringParserFactory::getInstance();

        $sUT->addVariable('test', 'myValue');

        $this->assertEquals(
            'myValue',
            $sUT->parse('{{ test }}')
        );

        $this->assertEquals(
            'myValue',
            $sUT->parse('{{ test }}')
        );
    }
}
