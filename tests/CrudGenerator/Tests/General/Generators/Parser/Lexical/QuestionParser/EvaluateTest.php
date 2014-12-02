<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionParser;

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
    public function testMalformedVar()
    {
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $context            = $this->createMock('CrudGenerator\Context\CliContext');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');

        $sUT = new QuestionParser(
            $context,
            $conditionValidator,
            QuestionTypeCollectionFactory::getInstance($context),
            new QuestionAnalyser()
        );

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $process = array(
            'questions' => array(
                'MyQuestion' => 'myQuestionValue',
            ),
        );

        $this->setexpectedexception('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testWithFiles()
    {
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $context            = $this->createMock('CrudGenerator\Context\CliContext');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');

        $sUT = new QuestionParser(
            $context,
            $conditionValidator,
            QuestionTypeCollectionFactory::getInstance($context),
            new QuestionAnalyser()
        );

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $process = array(
                'questions' => array(
                        array(
                                'dtoAttribute'    => 'test',
                                'text'            => 'test',
                                'defaultResponse' => 'myDefaultResponse',
                        ),
                ),
        );

        $this->assertEquals(
                $generator,
            $sUT->evaluate($process, $phpParser, $generator, false)
        );
    }

    public function testWithDependencyCondiction()
    {
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $context            = $this->createMock('CrudGenerator\Context\CliContext');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('myDefaultResponse')
        ->will($this->returnValue('myDefaultResponse'));

        $conditionValidator->expects($this->exactly(2))
        ->method('isValid')
        ->will($this->returnValue(true));

        $sUT = new QuestionParser(
            $context,
            $conditionValidator,
            QuestionTypeCollectionFactory::getInstance($context),
            new QuestionAnalyser()
        );

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());

        $process = array(
            'questions' => array(
                array(
                    'dtoAttribute'    => 'test',
                    'text'            => 'test',
                    'defaultResponse' => 'myDefaultResponse',
                    DependencyCondition::NAME => '!ArchitedGenerator',
                ),
                array(
                    'dtoAttribute' => 'test',
                    'text'         => 'test',
                    'type'         => QuestionTypeEnum::COMPLEX,
                    'factory'      => 'CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionFactory',
                ),
            ),
        );

        $this->assertEquals(
            $generator,
            $sUT->evaluate($process, $phpParser, $generator, false)
        );
    }
}
