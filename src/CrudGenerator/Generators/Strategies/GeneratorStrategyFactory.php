<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Strategies;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Context\ContextInterface;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class GeneratorStrategyFactory
{
    /**
     * @var GeneratorStrategy
     */
    private static $instance = null;

    /**
     * @param  ContextInterface                                       $context
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\Generators\Strategies\GeneratorStrategy
     */
    public static function getInstance(ContextInterface $context)
    {
        if (self::$instance === null) {
            self::$instance = new GeneratorStrategy(ViewFactory::getInstance());
        }

        return self::$instance;
    }
}
