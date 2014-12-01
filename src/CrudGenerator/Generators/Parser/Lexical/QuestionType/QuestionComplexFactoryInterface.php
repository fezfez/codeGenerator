<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical\QuestionType;

use CrudGenerator\Context\ContextInterface;

interface QuestionComplexFactoryInterface
{
    /**
     * @param  ContextInterface         $context
     * @return QuestionComplexInterface
     */
    public static function getInstance(ContextInterface $context);
};
