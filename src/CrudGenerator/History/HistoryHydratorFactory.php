<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\History;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestionFactory;
use CrudGenerator\Generators\Questions\Metadata\MetadataQuestionFactory;
use KeepUpdate\ArrayValidatorFactory;

/**
 * HistoryManager instance
 *
 * @author Stéphane Demonchaux
 */
class HistoryHydratorFactory
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\History\HistoryHydrator
     */
    public static function getInstance(ContextInterface $context)
    {
        return new HistoryHydrator(
            MetadataSourceConfiguredQuestionFactory::getInstance($context),
            MetadataQuestionFactory::getInstance($context),
            ArrayValidatorFactory::getInstance()
        );
    }
}
