<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexFactoryInterface;

class MyFakeQuestionNotWellConfiguredFactory implements QuestionComplexFactoryInterface
{
    /**
     * @param  ContextInterface      $context
     * @return MyFakeQuestionFactory
     */
    public static function getInstance(ContextInterface $context)
    {
        return new MyFakeQuestionFactory();
    }
}
