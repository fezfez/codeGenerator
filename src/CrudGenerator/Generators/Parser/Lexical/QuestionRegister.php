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
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollection;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;

class QuestionRegister implements ParserInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;
    /**
     * @var ConditionValidator
     */
    private $conditionValidator = null;
    /**
     * @var QuestionTypeCollection
     */
    private $questionTypeCollection = null;
    /**
     * @var QuestionAnalyser
     */
    private $questionAnalyser = null;

    /**
     * @param ContextInterface $context
     * @param ConditionValidator $dependencyCondition
     * @param QuestionTypeCollection $conditionValidator
     * @param QuestionAnalyser $questionAnalyser
     */
    public function __construct(
        ContextInterface $context,
        ConditionValidator $conditionValidator,
        QuestionTypeCollection $questionTypeCollection,
        QuestionAnalyser $questionAnalyser
    ) {
        $this->context                = $context;
        $this->conditionValidator     = $conditionValidator;
        $this->questionTypeCollection = $questionTypeCollection;
        $this->questionAnalyser       = $questionAnalyser;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($process['questions']) === true) {
            foreach ($process['questions'] as $question) {
                if (is_array($question) === false) {
                    throw new MalformedGeneratorException(
                        sprintf('Questions excepts to be an array "%s" given', gettype($question))
                    );
                }

                if ($this->conditionValidator->isValid($question, $generator, $parser) === true) {
                    $generator = $this->evaluateQuestions($question, $parser, $generator, $firstIteration, $process);
                }
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
    public function evaluateQuestions(
        array $question,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration,
        array $process
    ) {
        $question  = $this->questionAnalyser->checkIntegrity($question);
        $isParsed  = false;

        foreach ($this->questionTypeCollection as $questionTypeParser) {
            /* @var $questionTypeParser \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface */
            if ($question['type']->is($questionTypeParser->getType()) === true) {
                $generator->getDto()->register($question, $questionTypeParser->isIterable($question));
                $isParsed  = true;
                break;
            }
        }

        if ($isParsed === false) {
            throw new \LogicException(sprintf('The question type "%s" havent found his parser', $question['type']));
        }

        return $generator;
    }
}
