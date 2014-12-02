<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;

use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;
use CrudGenerator\Tests\TestCase;

class IsIterableTest extends TestCase
{
    public function testWellParsed()
    {
        $context   = $this->createMock('CrudGenerator\Context\CliContext');

        $sUT = new QuestionTypeComplex($context);

        $questionArray = array(
            'factory' => 'CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionFactory',
        );

        $this->assertFalse($sUT->isIterable($questionArray));
    }
}
