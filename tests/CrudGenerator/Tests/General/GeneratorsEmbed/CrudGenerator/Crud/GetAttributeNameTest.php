<?php
namespace CrudGenerator\Tests\General\Generators\CrudGenerator\Crud;

use CrudGenerator\Generators\CrudGenerator\Crud;

class GetAttributeNameTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new Crud();

        $sUT->setAttributeName('attribute', 'value');

        $this->assertEquals(array('attribute' => 'value'), $sUT->getAttributeName());
        $this->assertEquals('value', $sUT->getAttributeName('attribute'));
        $this->assertEquals(null, $sUT->getAttributeName('attddzribute'));
    }
}
