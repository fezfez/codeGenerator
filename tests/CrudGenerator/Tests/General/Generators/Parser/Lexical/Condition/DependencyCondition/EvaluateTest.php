<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\DependencyCondition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\GeneratorParser;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionInterface;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testDifferent()
    {
        $sUT       = new DependencyCondition();
        $generator = new GeneratorDataObject();

        $this->assertEquals(
            true,
            $sUT->isValid('ArchitectGenerator ' . ConditionInterface::UNDEFINED, $generator)
        );
    }

    public function testInd()
    {
        $sUT                 = new DependencyCondition();
        $generator           = new GeneratorDataObject();
        $generatorDependency = new GeneratorDataObject();

        $generatorDependency->setName('ArchitectGenerator');
        $generator->addDependency($generatorDependency);

        $this->assertEquals(
            true,
            $sUT->isValid('ArchitectGenerator', $generator)
        );
    }

    public function testWithout()
    {
        $sUT       = new DependencyCondition();
        $generator = new GeneratorDataObject();

        $this->assertEquals(
            false,
            $sUT->isValid('ArchitectGenerator', $generator)
        );
    }
}
