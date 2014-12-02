<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\DependencyCondition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionInterface;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Utils\PhpStringParserFactory;

class IsValidTest extends \PHPUnit_Framework_TestCase
{
    public function testDifferent()
    {
        $sUT          = new DependencyCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();

        $this->assertEquals(
            true,
            $sUT->isValid(array('ArchitectGenerator ' . ConditionInterface::UNDEFINED), $generator, $stringParser)
        );
    }

    public function testIn()
    {
        $sUT                 = new DependencyCondition();
        $generator           = new GeneratorDataObject();
        $stringParser        = PhpStringParserFactory::getInstance();
        $generatorDependency = new GeneratorDataObject();

        $generatorDependency->setName('ArchitectGenerator');
        $generator->addDependency($generatorDependency);

        $this->assertEquals(
            true,
            $sUT->isValid(array('ArchitectGenerator'), $generator, $stringParser)
        );
    }

    public function testWithout()
    {
        $sUT          = new DependencyCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();

        $this->assertEquals(
            false,
            $sUT->isValid(array('ArchitectGenerator'), $generator, $stringParser)
        );
    }
}
