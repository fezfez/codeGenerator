<?php
namespace CrudGenerator\Tests\General\View\Helpers\FixtureRenderer;

use CrudGenerator\View\Helpers\Text;

class ToCamelCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {
        $sUT = new Text();

        $this->assertEquals(
            'tiTi',
            $sUT->toCamelCase('ti_ti')
        );

        $this->assertEquals(
            'TiTi',
            $sUT->toCamelCase('ti_ti', true)
        );
    }
}
