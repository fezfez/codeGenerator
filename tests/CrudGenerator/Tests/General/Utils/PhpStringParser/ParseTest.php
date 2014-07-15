<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\Utils\PhpStringParser;

class ParseTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sUT = new PhpStringParser(array('test' => 'myValue'));

        $this->assertEquals(
            'myValue',
            $sUT->parse('<?php $test; ?>')
        );

        $this->assertEquals(
            'myValue',
            $sUT->parse('<?php $test ?>')
        );
    }
}
