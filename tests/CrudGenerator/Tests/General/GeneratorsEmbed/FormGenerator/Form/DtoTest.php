<?php
namespace CrudGenerator\Tests\General\Generators\FormGenerator\Form;

use CrudGenerator\GeneratorsEmbed\FormGenerator\Form;

class DtoTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new Form();
        $sUT->setNamespace('toto\titi')
            ->setModelName('MoodelName')
            ->setAttributeName('key', 'value');

        $this->assertEquals('toto\titi', $sUT->getNamespace());
        $this->assertEquals('MoodelName', $sUT->getModelName());
        $this->assertEquals(array('key' => 'value'), $sUT->getAttributeName());
        $this->assertEquals('value', $sUT->getAttributeName('key'));
        $this->assertEquals('/Form/', $sUT->getFormPath());
    }
}
