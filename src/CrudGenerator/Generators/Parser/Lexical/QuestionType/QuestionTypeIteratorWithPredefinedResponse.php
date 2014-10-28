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
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;

class QuestionTypeIteratorWithPredefinedResponse implements QuestionTypeInterface
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
     * @param ContextInterface $context
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
    public function evaluateQuestion(array $question, PhpStringParser $parser, GeneratorDataObject $generator)
    {
        $iterator       = $this->iteratorValidator->retrieveValidIteration($question, $generator, $parser);
        $iteratorParser = clone $parser;

        foreach ($iterator as $iteration) {

            $iteratorParser->addVariable('iteration', $iteration);

            $origine            = $iteratorParser->parse($question['iteration']['retrieveBy']);
            $responseCollection = new PredefinedResponseCollection();

            foreach ($question['iteration']['response']['predefined'] as $predefinedResponse) {
                $responseCollection->append(
                    new PredefinedResponse(
                        $predefinedResponse['response'],
                        $iteratorParser->parse($predefinedResponse['text']),
                        $predefinedResponse['response']
                    )
                );
            }

            $questionWithPredefinedResponse = new QuestionWithPredefinedResponse(
                $iteratorParser->parse($question['iteration']['text']),
                'set_predefined_' . ucfirst($question['dtoAttribute']) . $origine,
                $responseCollection
            );

            $questionWithPredefinedResponse->setDefaultResponse(
                (isset($question['iteration']['response']['default']) === true) ?
                    $iteratorParser->parse($question['iteration']['response']['default']) : null
            )->setRequired($question['required'])
             ->setResponseType(new QuestionResponseTypeEnum($question['iteration']['response']['type']));

            $response = $this->context->askCollection($questionWithPredefinedResponse);

            $questionName = $question['setter'];
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
        return QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::isIterable()
     */
    public function isIterable(array $question)
    {
        return true;
    }
}
