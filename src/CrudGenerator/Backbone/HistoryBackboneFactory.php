<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Backbone;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Questions\History\HistoryQuestionFactory;

class HistoryBackboneFactory
{
    /**
     * @param  ContextInterface $context
     * @return HistoryBackbone
     */
    public static function getInstance(ContextInterface $context)
    {
        return new HistoryBackbone(
            HistoryQuestionFactory::getInstance($context),
            $context
        );
    }
}
