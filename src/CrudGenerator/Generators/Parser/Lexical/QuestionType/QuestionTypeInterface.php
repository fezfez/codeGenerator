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

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

interface QuestionTypeInterface
{
    /**
     * Build the question and ask them
     *
     * @param array               $question
     * @param PhpStringParser     $parser
     * @param GeneratorDataObject $generator
     *
     * @return GeneratorDataObject
     */
    public function evaluateQuestion(array $question, PhpStringParser $parser, GeneratorDataObject $generator);

    /**
     * Return the type of parse in string
     *
     * @return string
     */
    public function getType();

    /**
     * @return boolean
     */
    public function isIterable(array $question);
};
