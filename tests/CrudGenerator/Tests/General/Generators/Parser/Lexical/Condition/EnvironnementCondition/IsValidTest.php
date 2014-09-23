<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\EnvironnementCondition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\DataObject;
use CrudGenerator\Utils\PhpStringParserFactory;

class IsValidTest extends \PHPUnit_Framework_TestCase
{
    public function testEquals()
    {
        $sUT          = new EnvironnementCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $this->assertEquals(
            true,
            $sUT->isValid(array('backend == pdo'), $generator, $stringParser)
        );
    }

    public function testDifferent()
    {
        $sUT          = new EnvironnementCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'test');

        $this->assertEquals(
            true,
            $sUT->isValid(array('backend != pdo'), $generator, $stringParser)
        );
    }

    public function testNotCatchDifferent()
    {
        $sUT          = new EnvironnementCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $this->assertEquals(
            false,
            $sUT->isValid(array('backend != pdo'), $generator, $stringParser)
        );
    }

    public function testNotCatchEquals()
    {
        $sUT          = new EnvironnementCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'test');

        $this->assertEquals(
            false,
            $sUT->isValid(array('backend == pdo'), $generator, $stringParser)
        );
    }

    public function testMalformedExpression()
    {
        $sUT          = new EnvironnementCondition();
        $generator    = new GeneratorDataObject();
        $stringParser = PhpStringParserFactory::getInstance();
        $generator->setDto(new DataObject());

        $this->setExpectedException('InvalidArgumentException');

        $sUT->isValid(array('backend pdo'), $generator, $stringParser);
    }
}
