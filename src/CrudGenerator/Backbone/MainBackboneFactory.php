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

class MainBackboneFactory
{
    /**
     * @param  ContextInterface                     $context
     * @return \CrudGenerator\Backbone\MainBackbone
     */
    public static function getInstance(ContextInterface $context)
    {
        return new MainBackbone(
            HistoryBackboneFactory::getInstance($context),
            SearchGeneratorBackboneFactory::getInstance($context),
            PreapreForGenerationBackboneFactory::getInstance($context),
            GenerateFileBackboneFactory::getInstance($context),
            GenerateBackboneFactory::getInstance($context),
            CreateSourceBackboneFactory::getInstance($context),
            $context
        );
    }
}
