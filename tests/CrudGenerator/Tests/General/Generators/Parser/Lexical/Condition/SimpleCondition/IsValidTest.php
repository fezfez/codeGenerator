<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\SimpleCondition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionInterface;
use CrudGenerator\Utils\PhpStringParserFactory;
use CrudGenerator\Generators\Parser\Lexical\Condition\SimpleCondition;

class IsValidTest extends \PHPUnit_Framework_TestCase
{
    public function testTrue()
    {
        $sUT          = new SimpleCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();

        $this->assertEquals(
            true,
            $sUT->isValid(array('{{ true or true }}'), $generator, $stringParser)
        );
    }

    public function testFalse()
    {
        $sUT          = new SimpleCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();

        $this->assertEquals(
            false,
            $sUT->isValid(array('{{ false or false }}'), $generator, $stringParser)
        );
    }

    public function testMultipleReturnFalse()
    {
        $sUT          = new SimpleCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();

        $this->assertEquals(
            false,
            $sUT->isValid(array('{{ true or true }}', '{{ false or false }}'), $generator, $stringParser)
        );
    }

    public function testMultipleReturnTrue()
    {
        $sUT          = new SimpleCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();

        $this->assertEquals(
            true,
            $sUT->isValid(array('{{ true or true }}', '{{ true or true }}'), $generator, $stringParser)
        );
    }
}
