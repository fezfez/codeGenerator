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

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class QuestionParser implements ParserInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;
    /**
     * @var DependencyCondition
     */
    private $dependencyCondition = null;

    /**
     * @param ContextInterface $context
     * @param DependencyCondition $dependencyCondition
     */
    public function __construct(ContextInterface $context, DependencyCondition $dependencyCondition)
    {
        $this->context             = $context;
        $this->dependencyCondition = $dependencyCondition;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($process['questions']) === true) {
            foreach ($process['questions'] as $question) {
                if (is_array($question) === false) {
                    throw new MalformedGeneratorException('Questions excepts to be an array "' . gettype($question) . "' given");
                }

                $generator = $this->evaluateQuestions($question, $parser, $generator, $firstIteration);
            }
        }

        //exit;

        return $generator;
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluateQuestions(array $question, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($question[GeneratorParser::DEPENDENCY_CONDITION]) === true) {
            $matches = $this->dependencyCondition->evaluate($question[GeneratorParser::DEPENDENCY_CONDITION], $parser, $generator, $firstIteration);
            foreach ($matches as $questionsMatchs) {
                $generator = $this->evaluateQuestions($questionsMatchs, $parser, $generator, $firstIteration);
            }
        } elseif (isset($question['type']) === true && $question['type'] === GeneratorParser::COMPLEX_QUESTION) {
            $complex   = $question['factory']::getInstance($this->context);
            $generator = $complex->ask($generator, $question);
        } else {
            $generator = $this->evaluateGeneriqueQuestion($question, $parser, $generator, $firstIteration);
        }

        return $generator;
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluateGeneriqueQuestion(array $question, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        $response = $this->context->ask(
            $question['text'],
            'set' . ucfirst($question['dtoAttribute']),
            (isset($question['defaultResponse']) === true) ? $parser->parse($question['defaultResponse']) : null,
            (isset($question['required']) === true) ? $question['required'] : false
        );

        $questionName = 'set' . ucfirst($question['dtoAttribute']);
        if (method_exists($generator->getDTO(), $questionName) === true && $response !== null) {
            $generator->getDTO()->$questionName($response);
        }

        return $generator;
    }
}
