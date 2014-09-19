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
use CrudGenerator\Utils\StaticPhp;

class QuestionTypeIteratorWithPredefinedResponse implements QuestionTypeInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;
    /**
     * @var StaticPhp
     */
    private $staticPhp = null;

    /**
     * @param ContextInterface $context
     * @param StaticPhp $staticPhp
     */
    public function __construct(ContextInterface $context, StaticPhp $staticPhp)
    {
        $this->context   = $context;
        $this->staticPhp = $staticPhp;
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
        $instance = $this->staticPhp->phpInterpretStatic(
            $question['collection'],
            array(
                lcfirst($process['name']) => $generator->getDto()
            )
        );

        if (($instance instanceof \Traversable) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The result of "%s" is not an instance of Traversable',
                    $question['collection']
                )
            );
        }

        foreach ($instance as $iteration) {

            $origine = $this->staticPhp->phpInterpretStatic(
                $question['origine'],
                array('iteration' => $iteration)
            );

            $responseCollection = new PredefinedResponseCollection();

            foreach ($question['predefinedResponse'] as $predefinedResponse) {
                $responseCollection->append(
                    new PredefinedResponse(
                        $predefinedResponse['response'],
                        $this->staticPhp->staticsprintf($predefinedResponse['text'], array('iteration' => $iteration)),
                        $predefinedResponse['response']
                    )
                );
            }

            $questionWithPredefinedResponse = new QuestionWithPredefinedResponse(
                $this->staticPhp->staticsprintf($question['text'], array('iteration' => $iteration)),
                'set_predefined_' . ucfirst($question['dtoAttribute']) . $origine,
                $responseCollection
            );

            $questionWithPredefinedResponse->setDefaultResponse(
                (isset($question['defaultResponse']) === true) ? $parser->parse($question['defaultResponse']) : null
            )->setRequired($question['required'])
             ->setResponseType($question['responseType']);


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
}
