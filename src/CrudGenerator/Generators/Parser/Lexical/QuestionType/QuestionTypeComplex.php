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
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;

class QuestionTypeComplex implements QuestionTypeInterface
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

    /**
     * @param array $question
     * @throws \Exception
     * @return \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexFactoryInterface
     */
    private function isCorrectFactory(array $question)
    {
        if (isset($question['factory']) === false) {
            throw new \Exception('Factory does no exist');
        } elseif (is_string($question['factory']) === false) {
            throw new \Exception('Must be a string');
        } elseif (false === class_exists($question['factory'], true)) {
            throw new \Exception('Class does not exist');
        } elseif (in_array('CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexFactoryInterface', class_implements($question['factory'])) === false) {
            throw new \Exception('Wrong implementation');
        }

        return $question['factory'];
    }
    /**
     * @param array $question
     * @return \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexInterface
     */
    private function getInstance(array $question)
    {
        $factory  = $this->isCorrectFactory($question);
        $instance = $factory::getInstance($this->context);

        if (is_object($instance) === false) {
            throw new \Exception('Factory must return object');
        } elseif (in_array('CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionComplexInterface', class_implements($instance)) === false) {
            throw new \Exception('Wrong implementation');
        }

        return $instance;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::evaluateQuestion()
     */
    public function evaluateQuestion(array $question, PhpStringParser $parser, GeneratorDataObject $generator)
    {
        return $this->getInstance($question)->ask($question, $parser, $generator);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::getType()
     */
    public function getType()
    {
        return QuestionTypeEnum::COMPLEX;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::isIterable()
     */
    public function isIterable(array $question)
    {
        return $this->getInstance($question)->isIterable($question);
    }
}
