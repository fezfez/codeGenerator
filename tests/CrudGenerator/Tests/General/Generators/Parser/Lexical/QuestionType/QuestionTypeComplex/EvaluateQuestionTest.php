<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;

use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidatorFactory;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParserFactory;
use CrudGenerator\DataObject;
use CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\Tests\TestCase;

class EvaluateQuestionTest extends TestCase
{
    public function testWellParsed()
    {
        $context   = $this->createMock('CrudGenerator\Context\CliContext');
        $phpParser = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $generator = new GeneratorDataObject();

        $sUT = new QuestionTypeComplex($context);

        $questionArray = array(
            'factory' => 'CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionFactory'
        );

        $dto       = new DataObject();
        $metadata  = new MetadataDataObjectMySQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $dto->setMetadata($metadata);
        $generator->setDto($dto);

        $this->assertInstanceOf(
            'CrudGenerator\Generators\GeneratorDataObject',
            $sUT->evaluateQuestion($questionArray, $phpParser, $generator)
        );
    }

    public function testFactoryNoSet()
    {
        $sUT = new QuestionTypeComplex($this->createMock('CrudGenerator\Context\CliContext'));

        $this->setExpectedException('Exception');

        $sUT->evaluateQuestion(
            array(),
            $this->createMock('CrudGenerator\Utils\PhpStringParser'),
            new GeneratorDataObject()
        );
    }

    public function testFactoryNotString()
    {
        $sUT = new QuestionTypeComplex($this->createMock('CrudGenerator\Context\CliContext'));

        $this->setExpectedException('Exception');

        $sUT->evaluateQuestion(
            array('factory' => null),
            $this->createMock('CrudGenerator\Utils\PhpStringParser'),
            new GeneratorDataObject()
        );
    }

    public function testFactoryClassDoesNotExist()
    {
        $sUT = new QuestionTypeComplex($this->createMock('CrudGenerator\Context\CliContext'));

        $this->setExpectedException('Exception');

        $sUT->evaluateQuestion(
            array('factory' => 'MyClass'),
            $this->createMock('CrudGenerator\Utils\PhpStringParser'),
            new GeneratorDataObject()
        );
    }

    public function testFactoryWrongImplementation()
    {
        $sUT = new QuestionTypeComplex($this->createMock('CrudGenerator\Context\CliContext'));

        $this->setExpectedException('Exception');

        $sUT->evaluateQuestion(
            array('factory' => __CLASS__),
            $this->createMock('CrudGenerator\Utils\PhpStringParser'),
            new GeneratorDataObject()
        );
    }

    public function testFactoryReturnNotWellImplementation()
    {
        $sUT = new QuestionTypeComplex($this->createMock('CrudGenerator\Context\CliContext'));

        $this->setExpectedException('Exception');

        $sUT->evaluateQuestion(
            array('factory' => 'CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionNotWellConfiguredFactory'),
            $this->createMock('CrudGenerator\Utils\PhpStringParser'),
            new GeneratorDataObject()
        );
    }
}
