<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical;

use CrudGenerator\Context\ContextInterface;

class MyFakeQuestionFactory
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        return new MyFakeQuestion();
    }
}
