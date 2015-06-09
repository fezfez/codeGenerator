<?php
namespace CrudGenerator\Tests\General\Utils\PhpStringParser;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

class StaticPhpTest extends \PHPUnit_Framework_TestCase
{
    public function testVariableDoesNotExst()
    {
        $sUT = new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            ),
            array('test' => 'myValue')
        );

        $this->setExpectedException('InvalidArgumentException');

        $sUT->staticPhp('$myVar');
    }

    public function testMethodReturnNull()
    {
        $sUT = new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            ),
            array('test' => 'myValue')
        );

        $sUT->addVariable('myVar', new GeneratorDataObject());
        $this->setExpectedException('InvalidArgumentException');

        $sUT->staticPhp('$myVar->getDto()->getMetadata()');
    }

    public function testMethodDoesNotExist()
    {
        $sUT = new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            ),
            array('test' => 'myValue')
        );

        $sUT->addVariable('myVar', new GeneratorDataObject());

        $this->setExpectedException('InvalidArgumentException');

        $sUT->staticPhp('$myVar->getDt()');
    }

    public function testOk()
    {
        $sUT = new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            ),
            array('test' => 'myValue')
        );

        $dto       = new DataObject();
        $generator = new GeneratorDataObject();
        $generator->setDto($dto);

        $sUT->addVariable('myVar', $generator);

        $this->assertEquals($dto, $sUT->staticPhp('$myVar->getDto()'));
    }
}
