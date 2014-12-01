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

class ConditionValidatorFactory
{
    /**
     * @return \CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator
     */
    public static function getInstance()
    {
        return new ConditionValidator(
            array(
                new DependencyCondition(),
                new EnvironnementCondition(),
                new SimpleCondition(),
            )
        );
    }
}
