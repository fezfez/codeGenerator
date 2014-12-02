<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionAnalyser;

use CrudGenerator\Generators\Parser\Lexical\QuestionAnalyser;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;

class CheckIntegrityTest extends \PHPUnit_Framework_TestCase
{
    public function testMissingMandatory()
    {
        $sUT = new QuestionAnalyser();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->checkIntegrity(array());
    }

    public function testWithWrongEnum()
    {
        $sUT = new QuestionAnalyser();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->checkIntegrity(array('dtoAttribute' => 'test', 'responseType' => 'toto'));
    }

    public function testWithWrongPredefinedResponseOne()
    {
        $sUT = new QuestionAnalyser();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->checkIntegrity(
            array(
                'dtoAttribute' => 'test',
                'type'         => QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE,
            )
        );
    }

    public function testWithWrongPredefinedResponseTwo()
    {
        $sUT = new QuestionAnalyser();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->checkIntegrity(
            array(
                'dtoAttribute' => 'test',
                'type'         => QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE,
                'iteration' => array(

                ),
            )
        );
    }

    public function testWithWrongPredefinedResponseThree()
    {
        $sUT = new QuestionAnalyser();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->checkIntegrity(
            array(
                'dtoAttribute' => 'test',
                'type'         => QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE,
                'iteration' => array(
                    'response' => array(
                    ),
                ),
            )
        );
    }

    public function testWithWrongPredefinedResponseFour()
    {
        $sUT = new QuestionAnalyser();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->checkIntegrity(
            array(
                'dtoAttribute' => 'test',
                'type'         => QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE,
                'iteration' => array(
                    'response' => array(
                        'predefined' => 'im not an array',
                    ),
                ),
            )
        );
    }
}
