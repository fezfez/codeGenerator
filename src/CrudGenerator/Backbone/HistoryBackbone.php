<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Backbone;

use CrudGenerator\Generators\Questions\History\HistoryQuestion;
use CrudGenerator\History\EmptyHistoryException;
use CrudGenerator\Context\ContextInterface;

class HistoryBackbone
{
    /**
     * @var HistoryQuestion
     */
    private $historyQuestion = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param HistoryQuestion  $historyQuestion
     * @param ContextInterface $context
     */
    public function __construct(HistoryQuestion $historyQuestion, ContextInterface $context)
    {
        $this->historyQuestion = $historyQuestion;
        $this->context         = $context;
    }

    /**
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function run()
    {
        try {
            $generator = $this->historyQuestion->ask();
            $this->context->publishGenerator($generator);

            return $generator;
        } catch (EmptyHistoryException $e) {
            $this->context->log("Generation history empty", "history_empty");
        }
    }
}
