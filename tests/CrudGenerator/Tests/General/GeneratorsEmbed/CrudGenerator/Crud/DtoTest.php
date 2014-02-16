<?php
namespace CrudGenerator\Tests\General\Generators\CrudGenerator\Crud;

use CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud;

class DtoTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new Crud();

        $sUT->setAttributeName('attribute', 'value')
        ->setWriteAction(true)
        ->setControllerName('MyController')
        ->setDisplayName('myName')
        ->setDisplayNames('MyNames')
        ->setPrefixRouteName('name-')
        ->setControllerDirectory('/src/Controller/')
        ->setViewDirectory('/view/mycontroller/');

        $this->assertEquals(array('attribute' => 'value'), $sUT->getAttributeName());
        $this->assertEquals('value', $sUT->getAttributeName('attribute'));
        $this->assertEquals(null, $sUT->getAttributeName('attddzribute'));
        $this->assertEquals(true, $sUT->getWriteAction());
        $this->assertEquals('MyController', $sUT->getControllerName());
        $this->assertEquals('myName', $sUT->getDisplayName());
        $this->assertEquals('MyNames', $sUT->getDisplayNames());
        $this->assertEquals('name-', $sUT->getPrefixRouteName());

        $this->assertEquals('/src/Controller/', $sUT->getControllerDirectory());
        $this->assertEquals('/view/mycontroller/', $sUT->getViewDirectory());
    }
}
