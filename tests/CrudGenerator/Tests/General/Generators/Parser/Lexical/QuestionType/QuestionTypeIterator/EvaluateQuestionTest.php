<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionType\QuestionTypeIterator;

use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeIterator;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidatorFactory;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParserFactory;

class EvaluateQuestionTest extends \PHPUnit_Framework_TestCase
{
    public function testdddddddddddddddddddddddddddddddd()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $iteratorValidator = new IteratorValidator(ConditionValidatorFactory::getInstance());

        $sUT = new QuestionTypeIterator($context, $iteratorValidator);

        $questionArray = array(
            'iteration' => array(
                'iterator' => '$architectGenerator->getMetadata()->getColumnCollection()',
                'retrieveBy' => '{{ iteration.getName() }}',
                'text' => 'Attribute name for \"{{ iteration.getName() }}\"',
                'response' => array(
                    'type' => 'text',
                    'default' => '{{ iteration.getName() }}'
                ),
                'condition' => array(
                    'simple' => array(
                        '{{ iteration.isPrimaryKey() == false }}'
                    )
                )
            )
        );

        $generator     = new GeneratorDataObject();
        $parser        = PhpStringParserFactory::getInstance();

        $sUT->evaluateQuestion($questionArray, $parser, $generator);
    }
}
