<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParser;

class AddVariableTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            )
        );

        $sUT->addVariable('myVariable', 'myValue');

        $this->assertEquals(true, $sUT->issetVariable('myVariable'));
    }
}
