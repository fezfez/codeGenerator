<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorDataObject;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;

class MethodTest extends \PHPUnit_Framework_TestCase
{
    public function testMethods()
    {
        $sUT = new GeneratorDataObject();
        $sUT->setDto(new DataObject());

        $sUT->addEnvironnementValue('backend', 'doctrine2');

        $this->assertEquals(
            array('backend' => 'doctrine2'),
            $sUT->getEnvironnementCollection()
        );
    }

    public function testFailAddEnvOnEmptyDto()
    {
        $sUT = new GeneratorDataObject();

        $this->setExpectedException('LogicException');

        $sUT->addEnvironnementValue('backend', 'doctrine2');
    }
}
