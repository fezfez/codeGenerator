<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\History;

use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Context\ContextInterface;

class HistoryQuestionFactory
{
    /**
     * @param  ContextInterface                                            $context
     * @return \CrudGenerator\Generators\Questions\History\HistoryQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        return new HistoryQuestion(HistoryFactory::getInstance($context), $context);
    }
}
