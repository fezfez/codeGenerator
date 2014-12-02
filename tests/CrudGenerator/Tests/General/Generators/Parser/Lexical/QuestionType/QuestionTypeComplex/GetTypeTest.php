<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;

use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;

class GetTypeTest extends TestCase
{
    public function testWellParsed()
    {
        $context   = $this->createMock('CrudGenerator\Context\CliContext');

        $sUT = new QuestionTypeComplex($context);

        $this->assertEquals(QuestionTypeEnum::COMPLEX, $sUT->getType());
    }
}
