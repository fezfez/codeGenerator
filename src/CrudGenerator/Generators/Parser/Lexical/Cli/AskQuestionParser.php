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
namespace CrudGenerator\Generators\Parser\Lexical\Cli;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Generators\Questions\Cli\DirectoryQuestion;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class AskQuestionParser implements ParserInterface
{
    /**
     * @var CliContext
     */
    private $cliContext = null;
    /**
     * @var DirectoryQuestion
     */
    private $directoryQuestion = null;
    /**
     * @var DependencyCondition
     */
    private $dependencyCondition = null;

    /**
     * @param CliContext $cliContext
     * @param DirectoryQuestion $directoryQuestion
     * @param DependencyCondition $dependencyCondition
     */
    public function __construct(CliContext $cliContext, DirectoryQuestion $directoryQuestion, DependencyCondition $dependencyCondition)
    {
        $this->cliContext         = $cliContext;
        $this->directoryQuestion  = $directoryQuestion;
        $this->dependencyCondition = $dependencyCondition;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
        if (isset($process['questions'])) {
            foreach ($process['questions'] as $question) {
                if (!is_array($question)) {
                    throw new MalformedGeneratorException('Questions excepts to be an array "' . gettype($question) . "' given");
                }

                $generator = $this->evaluateQuestions($question, $parser, $generator, $questions, $firstIteration);
            }
        }

        return $generator;
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param array $questions
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateQuestions(array $question, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
        if(isset($question[GeneratorParser::DEPENDENCY_CONDITION])) {
            $matches = $this->dependencyCondition->evaluate($question[GeneratorParser::DEPENDENCY_CONDITION], $parser, $generator, $questions, $firstIteration);
            foreach ($matches as $questionsMatchs) {
                $generator = $this->evaluateQuestions($questionsMatchs, $parser, $generator, $questions, $firstIteration);
            }
        } elseif (isset($question['type']) && $question['type'] === GeneratorParser::COMPLEX_QUESTION) {
            $complex = $question['factory']::getInstance($this->cliContext);
            $generator = $complex->ask($generator, $question);
        } elseif (isset($question['dtoAttribute']) && method_exists($generator->getDTO(), 'set' . ucfirst($question['dtoAttribute']))) {

            $response = $this->cliContext->getDialogHelper()->ask(
                $this->cliContext->getOutput(),
                '<question>' . $question['text'] . '</question> ',
                (isset($question['defaultResponse'])) ? $parser->parse($question['defaultResponse']) : null
            );

            $questionName = 'set' . ucfirst($question['dtoAttribute']);
            $generator->getDTO()->$questionName($response);
        }

        return $generator;
    }
}
