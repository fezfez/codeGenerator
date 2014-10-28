<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\Lexical\QuestionAnalyser;
use CrudGenerator\Generators\Parser\Lexical\DirectoriesParser;
use CrudGenerator\Generators\Parser\Lexical\FileParser;
use CrudGenerator\Generators\Parser\Lexical\TemplateVariableParser;
use CrudGenerator\Generators\Parser\Lexical\QuestionParser;
use CrudGenerator\Generators\Parser\Lexical\EnvironnementParser;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollectionFactory;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidatorFactory;
use CrudGenerator\Generators\Parser\Lexical\QuestionRegister;

class ParserCollectionFactory
{
    /**
     * @param ContextInterface $context
     * @throws \InvalidArgumentException
     * @return ParserCollection
     */
    public static function getInstance(ContextInterface $context)
    {
        $fileManager         = new FileManager();
        $collection          = new ParserCollection();
        $conditionValidation = ConditionValidatorFactory::getInstance();
        $iteratorValidator   = new IteratorValidator($conditionValidation);

        $collection->addPreParse(
                       new QuestionRegister(
                           $context,
                           $conditionValidation,
                           QuestionTypeCollectionFactory::getInstance($context),
                           new QuestionAnalyser()
                       )
                   )
                   ->addPreParse(new EnvironnementParser($context))
                   ->addPostParse(new DirectoriesParser())
                   ->addPostParse(
                       new QuestionParser(
                           $context,
                           $conditionValidation,
                           QuestionTypeCollectionFactory::getInstance($context),
                           new QuestionAnalyser()
                       )
                   )
                   ->addPostParse(new TemplateVariableParser($conditionValidation))
                   ->addPostParse(new FileParser($fileManager, $conditionValidation, $iteratorValidator));

        return $collection;
    }
}
