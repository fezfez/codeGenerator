<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParser;

class ParseTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            ),
            array('test' => 'myValue')
        );

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
