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

interface ConditionInterface
{
    const CONDITION       = 'condition';
    const CONDITION_ELSE  = 'else';
    const DIFFERENT       = '!';
    const UNDEFINED       = ' == undefined';
    const EQUAL           = '==';
    const DIFFERENT_EQUAL = '!=';

    /**
     * @param  array               $plainTextCondition
     * @param  GeneratorDataObject $generator
     * @param  PhpStringParser     $phpStringParser
     * @return boolean
     */
    public function isValid(
        array $plainTextCondition,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    );
}
