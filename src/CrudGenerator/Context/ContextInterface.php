<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CrudGenerator\Context;

use CrudGenerator\Generators\GeneratorDataObject;

/**
 * Context interface used for the interaction with the user.
 *
 */
interface ContextInterface
{
    /**
     * Ask question
     * @param  SimpleQuestion $question
     * @return string
     */
    public function ask(SimpleQuestion $question);

    /**
     * Ask question in collection
     *
     * @param QuestionWithPredefinedResponse $questionResponseCollection
     *
     * @return ResponseContext
     */
    public function askCollection(QuestionWithPredefinedResponse $questionResponseCollection);

    /**
     * Ask question in confirm mode ex : "Do you want to eat the banana ?" yes/no
     *
     * @param  string  $text
     * @param  string  $uniqueKey
     * @return boolean
     */
    public function confirm($text, $uniqueKey);

    /**
     * Create a menu. The idea is to create an "tree decision"
     *
     * @param  string   $text
     * @param  string   $uniqueKey
     * @param  callable $runner
     * @return void
     */
    public function menu($text, $uniqueKey, callable $runner);

    /**
     * Log a text
     *
     * @param  string|array $text
     * @param  string       $name
     * @return void
     */
    public function log($text, $name = null);

    /**
     * Publish the generatorDto
     *
     * @param  GeneratorDataObject $generator
     * @return void
     */
    public function publishGenerator(GeneratorDataObject $generator);
}
