<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\EnvironnementCondition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\DataObject;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testEquals()
    {
        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $this->assertEquals(
            true,
            $sUT->isValid('backend == pdo', $generator)
        );
    }

    public function testDifferent()
    {
        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'test');

        $this->assertEquals(
            true,
            $sUT->isValid('backend != pdo', $generator)
        );
    }

    public function testNotCatchDifferent()
    {
        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $this->assertEquals(
            false,
            $sUT->isValid('backend != pdo', $generator)
        );
    }

    public function testNotCatchEquals()
    {
        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'test');

        $this->assertEquals(
            false,
            $sUT->isValid('backend == pdo', $generator)
        );
    }

    public function testMalformedExpression()
    {
        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $this->setExpectedException('InvalidArgumentException');

        $sUT->isValid('backend pdo', $generator);
    }
}
