<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\FileConflict\FileConflictManagerFactory;
use CrudGenerator\Generators\Strategies\StrategyInterface;
use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Utils\FileManager;

/**
 * @author Stéphane Demonchaux
 */
class GeneratorFactory
{
    /**
     * @param  ContextInterface          $context
     * @param  StrategyInterface         $strategy
     * @throws \InvalidArgumentException
     * @return Generator
     */
    public static function getInstance(ContextInterface $context, StrategyInterface $strategy)
    {
        return new Generator(
            $strategy,
            FileConflictManagerFactory::getInstance($context),
            new FileManager(),
            HistoryFactory::getInstance($context),
            $context
        );
    }
}
