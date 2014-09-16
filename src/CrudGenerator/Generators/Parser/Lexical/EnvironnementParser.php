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
namespace CrudGenerator\Generators\Parser\Lexical;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class EnvironnementParser implements ParserInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($process['environnement']) === true && is_array($process['environnement']) === true) {
            foreach ($process['environnement'] as $environnementName => $question) {
                if (is_array($question) === false) {
                    throw new MalformedGeneratorException(
                        sprintf('Questions excepts to be an array "%s" given', gettype($question))
                    );
                }

                $generator = $this->evaluateQuestions(
                    $environnementName,
                    $question,
                    $parser,
                    $generator,
                    $firstIteration
                );
            }
        }

        return $generator;
    }

    /**
     * @param string $environnementName
     * @param array $environnements
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateQuestions(
        $environnementName,
        array $environnements,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
        $responseCollection = new PredefinedResponseCollection();
        $toRecurse          = array();
        foreach ($environnements as $framework => $environnement) {
            if (is_array($environnement) === true) {
                $responseCollection->append(new PredefinedResponse($framework, $framework, $framework));
                $toRecurse[$framework] = $environnement;
            } else {
                $responseCollection->append(new PredefinedResponse($environnement, $environnement, $environnement));
            }
        }

        $question = new QuestionWithPredefinedResponse(
            $environnementName . ' environnement',
            'environnement_' . $environnementName,
            $responseCollection
        );

        $response = $this->context->askCollection($question);


        if ($response !== null) {
            $generator->addEnvironnementValue($environnementName, $response);

            if (isset($toRecurse[$response]) === true) {
                foreach ($toRecurse[$response] as $recurseEnvironnementName => $questionToRecurse) {
                    $this->evaluateQuestions(
                        $recurseEnvironnementName,
                        $questionToRecurse,
                        $parser,
                        $generator,
                        $firstIteration
                    );
                }
            }
        }

        return $generator;
    }
}
