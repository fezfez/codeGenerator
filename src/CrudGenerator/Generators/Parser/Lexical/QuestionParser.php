<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollection;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;

class QuestionParser implements ParserInterface
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
     * @param ContextInterface   $context
     * @param ConditionValidator $conditionValidator
     * @param QuestionAnalyser   $questionAnalyser
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
                    $generator = $this->evaluateQuestions($question, $parser, $generator);
                }
            }
        }

        return $generator;
    }

    /**
     * @param  array               $question
     * @param  PhpStringParser     $parser
     * @param  GeneratorDataObject $generator
     * @return GeneratorDataObject
     */
    public function evaluateQuestions(array $question, PhpStringParser $parser, GeneratorDataObject $generator)
    {
        $question = $this->questionAnalyser->checkIntegrity($question);
        $isParsed = false;

        foreach ($this->questionTypeCollection as $questionTypeParser) {
            /* @var $questionTypeParser \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface */
            /* @var $type \CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum */
            $type = $question['type'];
            if ($type->is($questionTypeParser->getType()) === true) {
                $generator = $questionTypeParser->evaluateQuestion($question, $parser, $generator);
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
