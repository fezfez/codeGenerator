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

use CrudGenerator\Utils\TranstyperFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParserFactory;
use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Validator\GeneratorValidatorFactory;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParserFactory
{
    /**
     * @param  ContextInterface $context
     * @return GeneratorParser
     */
    public static function getInstance(ContextInterface $context)
    {
        $generatorFinder  = GeneratorFinderFactory::getInstance();
        $parserCollection = ParserCollectionFactory::getInstance($context);

        return new GeneratorParser(
            new FileManager(),
            TranstyperFactory::getInstance(),
            PhpStringParserFactory::getInstance(),
            $generatorFinder,
            $parserCollection,
            GeneratorValidatorFactory::getInstance()
        );
    }
}
