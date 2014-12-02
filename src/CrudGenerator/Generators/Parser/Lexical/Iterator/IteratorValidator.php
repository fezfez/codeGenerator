<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical\Iterator;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;
use CrudGenerator\Utils\PhpStringParser;

class IteratorValidator
{
    /**
     * @var ConditionValidator
     */
    private $conditionValidator = null;

    /**
     * @param ConditionValidator $conditionValidator
     */
    public function __construct(ConditionValidator $conditionValidator)
    {
        $this->conditionValidator = $conditionValidator;
    }

    /**
     * @param  array                     $node
     * @param  GeneratorDataObject       $generator
     * @param  PhpStringParser           $phpStringParser
     * @throws \InvalidArgumentException
     * @return array
     */
    public function retrieveValidIteration(
        array $node,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $overIteratorParser = clone $phpStringParser;
        $validIteration     = array();
        $iterator           = $overIteratorParser->staticPhp($node['iteration']['iterator']);

        if (is_array($iterator) === false && ($iterator instanceof \Traversable) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The result of "%s" is not an instance of Traversable',
                    $node['iteration']['iterator']
                )
            );
        }

        foreach ($iterator as $iteration) {
            $overIteratorParser->addVariable('iteration', $iteration);

            if ($this->conditionValidator->isValid($node['iteration'], $generator, $phpStringParser) === false) {
                continue;
            }

            $validIteration[] = $iteration;
        }

        return $validIteration;
    }
}
