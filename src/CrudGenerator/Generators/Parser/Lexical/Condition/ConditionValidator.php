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

class ConditionValidator
{
    /**
     * @var array
     */
    private $conditionCollection = null;

    /**
     * @param array $conditionCollection
     */
    public function __construct(array $conditionCollection)
    {
        $this->conditionCollection = $conditionCollection;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $node,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $isValid = true;

        // Have condition ?
        if (isset($node[ConditionInterface::CONDITION]) === true) {
            $condition = $node[ConditionInterface::CONDITION];
            $validator = $this->findValidator($condition);

            if ($validator->isValid($condition[$validator::NAME], $generator, $phpStringParser) === false) {
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * @param array $condition
     * @throws \Exception
     * @return ConditionInterface
     */
    private function findValidator(array $condition)
    {
        foreach ($this->conditionCollection as $conditionChecker) {
            if (isset($condition[$conditionChecker::NAME]) === true) {
                return $conditionChecker;
            }
        }

        throw new \Exception(sprintf('Condition "%s" does not exist', json_encode($condition)));
    }
}
