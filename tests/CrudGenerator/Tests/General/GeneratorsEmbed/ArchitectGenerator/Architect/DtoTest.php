<?php
namespace CrudGenerator\Tests\General\Generators\ArchitectGenerator\Architect;

use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class DtoTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new Architect();

        $sUT->setAttributeName('attribute', 'value')
        ->setModelName('ModelName')
        ->setNamespace('MyNamespace')
        ->setUnitTestDirectory('MyDir');

        $this->assertEquals(array('attribute' => 'value'), $sUT->getAttributeName());
        $this->assertEquals('value', $sUT->getAttributeName('attribute'));
        $this->assertEquals(null, $sUT->getAttributeName('attddzribute'));
        $this->assertEquals('ModelName', $sUT->getModelName());
        $this->assertEquals('MyNamespace', $sUT->getNamespace());
        $this->assertEquals('MyDir', $sUT->getUnitTestDirectory());
    }
}
