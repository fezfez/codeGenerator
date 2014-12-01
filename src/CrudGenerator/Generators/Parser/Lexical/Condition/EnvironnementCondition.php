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

class EnvironnementCondition implements ConditionInterface
{
    const NAME = 'environnement';

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $expressions,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        foreach ($expressions as $expression) {
            try {
                $comparaisonDifferentEquals = $this->analyseExpressionType(
                    $expression,
                    ConditionInterface::DIFFERENT_EQUAL
                );
                $addEnvironnementExpression = (
                    $comparaisonDifferentEquals['environnementValue'] !==
                    $generator->getEnvironnement($comparaisonDifferentEquals['environnementName'])
                );
            } catch (\InvalidArgumentException $e) {
                $comparaisonEquals          = $this->analyseExpressionType($expression, ConditionInterface::EQUAL);
                $addEnvironnementExpression = (
                    $comparaisonEquals['environnementValue'] ===
                    $generator->getEnvironnement($comparaisonEquals['environnementName'])
                );
            }

            if ($addEnvironnementExpression === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  string                    $expression
     * @param  string                    $type
     * @throws \InvalidArgumentException
     * @return array
     */
    private function analyseExpressionType($expression, $type)
    {
        $expressionExplode = array_map('trim', explode($type, $expression));

        if (count($expressionExplode) === 2) {
            return array(
                'environnementName'  => $expressionExplode[0],
                'environnementValue' => $expressionExplode[1],
            );
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Unknown expression %s',
                $expression
            )
        );
    }
}
