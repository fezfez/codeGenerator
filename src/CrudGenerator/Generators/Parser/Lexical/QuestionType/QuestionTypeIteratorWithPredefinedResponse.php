<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
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
    public function evaluateQuestion(
        array $question,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration,
        array $process
    ) {
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
             ->setResponseType($question['iteration']['response']['type']);


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
