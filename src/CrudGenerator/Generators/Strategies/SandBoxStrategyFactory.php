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

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\View\ViewFactory;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class SandBoxStrategyFactory
{
    /**
     * @param  ContextInterface                                     $context
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\Generators\Strategies\SandBoxStrategy
     */
    public static function getInstance(ContextInterface $context)
    {
        $view = ViewFactory::getInstance();

        return new SandBoxStrategy($view, $context);
    }
}
