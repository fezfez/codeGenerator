<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical\Condition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

class SimpleCondition implements ConditionInterface
{
    const NAME = 'simple';

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $nodes,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $parser = clone $phpStringParser;

        foreach ($nodes as $node) {
            if (false === (bool) $parser->parse($node)) {
                return false;
            }
        }

        return true;
    }
}
