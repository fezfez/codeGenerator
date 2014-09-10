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

                $generator = $this->evaluateQuestions($question, $parser, $generator, $firstIteration, $process);
            }
        }

        return $generator;
    }

    /**
     * @param array $question
     * @param GeneratorDataObject $generator
     * @param string $type
     * @return GeneratorDataObject
     */
    private function registerQuestionToDTO(array $question, GeneratorDataObject $generator, $type = null)
    {
        if ($type === null) {
            $type = (isset($question['responseType']) ? $question['responseType'] : null);
        }

        $generator->getDTO()->register($question['dtoAttribute'], $type);

        return $generator;
    }

    private function checkIntegrity(array $question)
    {
        $exception = function($question, $missingAttr) {
            throw new MalformedGeneratorException(
                sprintf(
                    'Question "%s" does not have property "%s"',
                    $question['text'],
                    $missingAttr
                )
            );
        };

        if (isset($question['type']) === false) {
            $type = new QuestionTypeEnum();
        } else {
            try {
                $type = new QuestionTypeEnum($question['type']);
            } catch (\UnexpectedValueException $e) {
                throw new MalformedGeneratorException($e->getMessage());
            }
        }

        if (isset($question['dtoAttribute']) === false) {
            $exception($question, 'dtoAttribute');
        }

        $question['type'] = $type;

        return $question;
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluateQuestions(array $question, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration, array $process)
    {
        if (isset($question[GeneratorParser::DEPENDENCY_CONDITION]) === true) {
            $matches = $this->dependencyCondition->evaluate(
                $question[GeneratorParser::DEPENDENCY_CONDITION],
                $parser,
                $generator,
                $firstIteration
            );
            foreach ($matches as $questionsMatchs) {
                $generator = $this->evaluateQuestions(
                    $questionsMatchs,
                    $parser,
                    $generator,
                    $firstIteration,
                    $process
                );
            }
        } else {
            $question = $this->checkIntegrity($question);
            if ($question['type']->is(QuestionTypeEnum::COMPLEX) === true) {
                $generator = $this->registerQuestionToDTO($question, $generator);
                $complex   = $question['factory']::getInstance($this->context);
                $generator = $complex->ask($generator, $question);
            } elseif ($question['type']->is(QuestionTypeEnum::DIRECTORY) === true) {
                $generator = $this->registerQuestionToDTO($question, $generator);
                $complex   = \CrudGenerator\Generators\Questions\DirectoryQuestionFactory::getInstance($this->context);
                $generator = $complex->ask($generator, $question);
            } elseif ($question['type']->is(QuestionTypeEnum::ITERATOR) === true) {
                $generator = $this->registerQuestionToDTO($question, $generator, 'collection');
                $generator = $this->evaluateIteratorQuestion($question, $parser, $generator, $firstIteration, $process);
            } elseif ($question['type']->is(QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE) === true) {
                $generator = $this->registerQuestionToDTO($question, $generator, 'collection');
                $generator = $this->evaluateIteratorWithPredefinedResponseQuestion($question, $parser, $generator, $firstIteration, $process);
            } else {
                $generator = $this->registerQuestionToDTO($question, $generator);
                $generator = $this->evaluateGeneriqueQuestion($question, $parser, $generator, $firstIteration);
            }
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
            (isset($question['required']) === true) ? $question['required'] : false,
            null,
            (isset($question['responseType']) === true) ? $question['responseType'] : null
        );

        $questionName = 'set' . ucfirst($question['dtoAttribute']);
        if ($response !== null) {
            $generator->getDTO()->$questionName($response);
        }

        return $generator;
    }

    /**
      * @param array $question
      * @param PhpStringParser $parser
      * @param GeneratorDataObject $generator
      * @param boolean $firstIteration
      * @param array $process
      * @throws \InvalidArgumentException
      */
    public function evaluateIteratorWithPredefinedResponseQuestion(
        array $question,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration,
        array $process
    ) {
        $instance = $this->phpInterpretStatic(
            $question['collection'],
            array(
                lcfirst($process['name']) => $generator->getDTO()
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

            $origine = $this->phpInterpretStatic(
                $question['origine'],
                array('iteration' => $iteration)
            );

            $responseList = array();
            foreach ($question['predefinedResponse'] as $key => $response) {
                $responseList[$key] = $this->staticsprintf($response, array('iteration' => $iteration));
            }

            $response = $this->context->askCollection(
                $this->staticsprintf($question['text'], array('iteration' => $iteration)),
                'set_predefined_' . ucfirst($question['dtoAttribute']) . $origine,
                $responseList,
                (isset($question['defaultResponse']) === true) ? $parser->parse($question['defaultResponse']) : null,
                (isset($question['required']) === true) ? $question['required'] : false,
                null,
                (isset($question['responseType']) === true) ? $question['responseType'] : null
            );

            $questionName = 'set' . ucfirst($question['dtoAttribute']);
            if ($response !== null) {
                $generator->getDTO()->$questionName($origine, $response);
            }
        }

        return $generator;
    }

    /**
     * @param string $text
     * @param array $realVariables
     * @return string
     */
    private function staticsprintf($text, array $realVariables)
    {
        $questionRawExplode = explode(',', $text);
        $questionText       = array_shift($questionRawExplode);
        $questionVariables  = array_map('trim', $questionRawExplode);

        $placeholder = array();
        foreach ($questionVariables as $questionVariable) {
            $placeholder[] = $this->phpInterpretStatic(
                $questionVariable,
                $realVariables
            );
        }

        return vsprintf($questionText, $placeholder);
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluateIteratorQuestion(
        array $question,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration,
        array $process
    ) {
        $instance = $this->phpInterpretStatic(
            $question['collection'],
            array(
                lcfirst($process['name']) => $generator->getDTO()
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

            $origine = $this->phpInterpretStatic(
                $question['origine'],
                array('iteration' => $iteration)
            );

            $response = $this->context->ask(
                $this->staticsprintf($question['text'], array('iteration' => $iteration)),
                'set' . ucfirst($question['dtoAttribute']) . $origine,
                (isset($question['defaultResponse']) === true) ? $parser->parse($question['defaultResponse']) : null,
                (isset($question['required']) === true) ? $question['required'] : false,
                null,
                (isset($question['responseType']) === true) ? $question['responseType'] : null
            );

            $questionName = 'set' . ucfirst($question['dtoAttribute']);
            if ($response !== null) {
                $generator->getDTO()->$questionName($origine, $response);
            }
        }

        return $generator;
    }

    /**
     * @param string $test
     * @param array $variableVariable
     * @throws \InvalidArgumentException
     * @return mixed
     */
    private function phpInterpretStatic($test, array $variableVariable)
    {
        $testExplode = explode('->', $test);

        $cleanMethodName = function($value) {
            return str_replace('()', '', $value);
        };

        $variableName = str_replace('$', '', $testExplode[0]);

        if (isset($variableVariable[$variableName]) === false) {
            throw new \InvalidArgumentException(sprintf('var %s does not exist', $variableName));
        }

        $method = $cleanMethodName($testExplode[1]);

        if (false === method_exists($variableVariable[$variableName], $method)) {
            throw new \InvalidArgumentException(sprintf('method %s does not exist on %s', $method, $test));
        }

        $instance = $variableVariable[$variableName]->$method();

        foreach ($testExplode as $key => $value) {
            if ($key === 0 || $key === 1) {
                continue;
            }

            if ($instance === null) {
                throw new \InvalidArgumentException(sprintf('method %s return null', $method));
            } else {
                $method = $cleanMethodName($value);
                $instance = $instance->$method();
            }
        }

        return $instance;
    }
}
