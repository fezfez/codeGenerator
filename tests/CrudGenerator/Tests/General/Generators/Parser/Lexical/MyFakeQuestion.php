<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical;

use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexInterface;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;

class MyFakeQuestion implements QuestionComplexInterface
{
    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexInterface::evaluateQuestion()
     */
    public function ask(array $question, PhpStringParser $parser, GeneratorDataObject $generator)
    {
        return $generator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexInterface::isIterable()
     */
    public function isIterable(array $question)
    {
        return false;
    }
}
