<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionRegister;

use CrudGenerator\Generators\Parser\Lexical\QuestionParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollectionFactory;
use CrudGenerator\Generators\Parser\Lexical\QuestionAnalyser;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Tests\TestCase;

class EvaluateTest extends TestCase
{
    public function testMalformedQuestion()
    {
        $conditionValidator     = $this->_('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $context                = $this->_('CrudGenerator\Context\CliContext');
        $phpParser              = $this->_('CrudGenerator\Utils\PhpStringParser');
        $questionTypeCollection = QuestionTypeCollectionFactory::getInstance($context);
        $questionAnalyser       = new QuestionAnalyser();

        $sUT = new QuestionParser($context, $conditionValidator, $questionTypeCollection, $questionAnalyser);

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $process = array(
            'questions' => array(
                'MyQuestion' => 'myQuestionValue'
            )
        );

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testWithConditionNotAllow()
    {
        $conditionValidator     = $this->_('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $context                = $this->_('CrudGenerator\Context\CliContext');
        $phpParser              = $this->_('CrudGenerator\Utils\PhpStringParser');
        $questionTypeCollection = QuestionTypeCollectionFactory::getInstance($context);
        $questionAnalyser       = new QuestionAnalyser();

        $sUT = new QuestionParser($context, $conditionValidator, $questionTypeCollection, $questionAnalyser);

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $process = array(
            'questions' => array(
                'MyQuestion' => array('myQuestionValue')
            )
        );

        $conditionValidatorExpects = $conditionValidator->expects($this->once());
        $conditionValidatorExpects->method('isValid');
        $conditionValidatorExpects->willReturn(false);

        $generator = $sUT->evaluate($process, $phpParser, $generator, true);

        $this->assertEquals(array(), $generator->getDto()->getStore());
    }
}
