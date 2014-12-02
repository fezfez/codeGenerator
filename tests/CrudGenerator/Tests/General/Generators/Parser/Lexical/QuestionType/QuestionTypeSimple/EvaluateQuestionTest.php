<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionType\QuestionTypeSimple;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeSimple;
use CrudGenerator\Tests\TestCase;

class EvaluateQuestionTest extends TestCase
{
    public function testWithNoResponse()
    {
        $context   = $this->createMock('CrudGenerator\Context\CliContext');
        $parser    = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $sUT       = new QuestionTypeSimple($context);
        $generator = new GeneratorDataObject();
        $dto       = new DataObject();

        $questionArray = array(
            'dtoAttribute' => 'toto',
            'setter'       => 'setToto',
            'required'     => false,
            'helpMessage'  => '',
            'responseType' => new QuestionResponseTypeEnum(),
            'text'         => 'im a question !',
        );

        $dto->register($questionArray, false);
        $generator->setDto($dto);

        $parserExpects = $parser->expects($this->never());
        $parserExpects->method('parse');

        $response = null;

        $contextExpects = $context->expects($this->once());
        $contextExpects->method('ask');
        $contextExpects->willReturn($response);

        $result = $sUT->evaluateQuestion($questionArray, $parser, $generator);

        $this->assertInstanceOf('CrudGenerator\Generators\GeneratorDataObject', $result);

        $this->assertEquals($response, $result->getDto()->getToto());
    }

    public function testWithResponse()
    {
        $context   = $this->createMock('CrudGenerator\Context\CliContext');
        $parser    = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $sUT       = new QuestionTypeSimple($context);
        $generator = new GeneratorDataObject();

        $generator->setDto(new DataObject());

        $questionArray = array(
            'dtoAttribute' => 'toto',
            'setter'       => 'setToto',
            'required'     => false,
            'helpMessage'  => '',
            'responseType' => new QuestionResponseTypeEnum(),
            'text'         => 'im a question !',
        );

        $generator->getDto()->register($questionArray, false);

        $parserExpects = $parser->expects($this->never());
        $parserExpects->method('parse');

        $response = 'tutu';

        $contextExpects = $context->expects($this->once());
        $contextExpects->method('ask');
        $contextExpects->willReturn($response);

        $result = $sUT->evaluateQuestion($questionArray, $parser, $generator);

        $this->assertInstanceOf('CrudGenerator\Generators\GeneratorDataObject', $result);

        $this->assertEquals($response, $result->getDto()->getToto());
    }
}
