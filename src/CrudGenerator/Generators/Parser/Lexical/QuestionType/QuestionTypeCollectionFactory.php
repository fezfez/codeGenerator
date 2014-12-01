<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical\QuestionType;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidatorFactory;

class QuestionTypeCollectionFactory
{
    /**
     * @param  ContextInterface       $context
     * @return QuestionTypeCollection
     */
    public static function getInstance(ContextInterface $context)
    {
        $iteratorValidator      = new IteratorValidator(ConditionValidatorFactory::getInstance());
        $questionTypeCollection = new QuestionTypeCollection();

        $questionTypeCollection->append(new QuestionTypeSimple($context))
                               ->append(new QuestionTypeIterator($context, $iteratorValidator))
                               ->append(new QuestionTypeIteratorWithPredefinedResponse($context, $iteratorValidator))
                               ->append(new QuestionTypeDirectory($context))
                               ->append(new QuestionTypeComplex($context));

        return $questionTypeCollection;
    }
}
