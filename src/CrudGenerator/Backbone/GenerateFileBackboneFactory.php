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
use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;

class GenerateFileBackboneFactory
{
    /**
     * @param  ContextInterface     $context
     * @return GenerateFileBackbone
     */
    public static function getInstance(ContextInterface $context)
    {
        return new GenerateFileBackbone(
            GeneratorFactory::getInstance($context, GeneratorStrategyFactory::getInstance($context)),
            $context
        );
    }
}
