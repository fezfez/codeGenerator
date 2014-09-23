<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\TemplateVariableParser;

use CrudGenerator\Generators\Parser\Lexical\TemplateVariableParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testMalformed()
    {
        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new TemplateVariableParser($conditionValidator);

        $generator = new GeneratorDataObject();

        $process = array('templateVariables' => array('test', 'test2'));

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testEmpty()
    {
        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new TemplateVariableParser($conditionValidator);

        $generator = new GeneratorDataObject();

        $process = array();

        $this->assertEquals(
            $generator,
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithVar()
    {
        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyValue')
        ->will($this->returnValue('MyValueParser'));

        $sUT = new TemplateVariableParser($conditionValidator);

        $generator = new GeneratorDataObject();

        $process = array(
            'templateVariables' => array(
                array(
                    'variableName' => 'MyVar',
                    'value' => 'MyValue'
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addTemplateVariable('MyVar', 'MyValueParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithDependencyCondiction()
    {
        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyValue')
        ->will($this->returnValue('MyValueParser'));

        $sUT = new TemplateVariableParser($conditionValidator);

        $generator = new GeneratorDataObject();

        $process = array(
            'templateVariables' => array(
                array(
                    'variableName' => 'MyVar',
                    'value' => 'MyValue',
                    DependencyCondition::NAME => '!ArchitedGenerator'
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addTemplateVariable('MyVar', 'MyValueParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithEnvironnemetnCondiction()
    {
        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyValue')
        ->will($this->returnValue('MyValueParser'));

        $sUT = new TemplateVariableParser($conditionValidator);

        $generator = new GeneratorDataObject();

        $process = array(
            'templateVariables' => array(
                array(
                    'variableName' => 'MyVar',
                    'value' => 'MyValue',
                    EnvironnementCondition::NAME => 'backend == pdo'
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addTemplateVariable('MyVar', 'MyValueParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }
}
