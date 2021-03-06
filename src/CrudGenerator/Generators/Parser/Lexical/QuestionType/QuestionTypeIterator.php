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
use CrudGenerator\Context\SimpleQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;
use CrudGenerator\Utils\PhpStringParser;

class QuestionTypeIterator implements QuestionTypeInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;
    /**
     * @var IteratorValidator
     */
    private $iteratorValidator = null;

    /**
     * @param ContextInterface  $context
     * @param IteratorValidator $iteratorValidator
     */
    public function __construct(ContextInterface $context, IteratorValidator $iteratorValidator)
    {
        $this->context           = $context;
        $this->iteratorValidator = $iteratorValidator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::evaluateQuestion()
     */
    public function evaluateQuestion(array $questionRaw, PhpStringParser $parser, GeneratorDataObject $generator)
    {
        $iterator       = $this->iteratorValidator->retrieveValidIteration($questionRaw, $generator, $parser);
        $iteratorParser = clone $parser;

        foreach ($iterator as $iteration) {
            $iteratorParser->addVariable('iteration', $iteration);

            $origine = $iteratorParser->parse($questionRaw['iteration']['retrieveBy']);

            $question = new SimpleQuestion(
                $iteratorParser->parse($questionRaw['iteration']['text']),
                $questionRaw['setter'] . $origine
            );
            $question->setDefaultResponse((isset($questionRaw['iteration']['response']['default']) === true)
                    ? $iteratorParser->parse($questionRaw['iteration']['response']['default']) : null);
            $question->setRequired($questionRaw['required']);
            $question->setHelpMessage($questionRaw['helpMessage']);
            $question->setResponseType(new QuestionResponseTypeEnum($questionRaw['iteration']['response']['type']));

            $response = $this->context->ask($question);

            $questionName = $questionRaw['setter'];
            if ($response !== null) {
                $generator->getDto()->$questionName($origine, $response);
            }
        }

        return $generator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::getType()
     */
    public function getType()
    {
        return QuestionTypeEnum::ITERATOR;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::isIterable()
     */
    public function isIterable(array $question)
    {
        return true;
    }
}
