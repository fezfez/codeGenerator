<?php
namespace CrudGenerator\Tests\General\Generators\FormGenerator\Form;

use CrudGenerator\GeneratorsEmbed\FormGenerator\Form;

class GetNamespacePathTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new Form();
        $sUT->setNamespace('toto\titi');

        $this->assertEquals('toto\titi', $sUT->getNamespace());
    }
}
