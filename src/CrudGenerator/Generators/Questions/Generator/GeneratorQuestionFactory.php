<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\Generator;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Finder\GeneratorFinderFactory;

class GeneratorQuestionFactory
{
    /**
     * @param  ContextInterface                                                $context
     * @return \CrudGenerator\Generators\Questions\Generator\GeneratorQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        $generatorFinder = GeneratorFinderFactory::getInstance();

        return new GeneratorQuestion($generatorFinder, $context);
    }
}
