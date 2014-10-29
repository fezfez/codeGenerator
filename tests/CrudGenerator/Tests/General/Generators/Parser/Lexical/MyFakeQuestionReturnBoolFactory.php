<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexFactoryInterface;

class MyFakeQuestionReturnBoolFactory implements QuestionComplexFactoryInterface
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        return true;
    }
}
