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
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

class EnvironnementParser implements ParserInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * Constructor.
     *
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
     * @param  string              $environnementName
     * @param  array               $environnements
     * @param  PhpStringParser     $parser
     * @param  GeneratorDataObject $generator
     * @param  boolean             $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateQuestions(
        $environnementName,
        array $environnements,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
        $question = new QuestionWithPredefinedResponse(
            $environnementName . ' environnement',
            'environnement_' . $environnementName,
            $this->buildResponseCollection($environnements)
        );

        $response = $this->context->askCollection($question);

        if ($response !== null) {
            $generator->addEnvironnementValue($environnementName, $response);

            $toRecurse = $this->buildToRecurse($environnements);

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

    /**
     * @param array $environnements
     * @return array
     */
    private function buildToRecurse(array $environnements)
    {
        $toRecurse = array();

        foreach ($environnements as $framework => $environnement) {
            if (is_array($environnement) === true) {
                $toRecurse[$framework] = $environnement;
            }
        }

        return $toRecurse;
    }

    /**
     * @param array $environnements
     * @return \CrudGenerator\Context\PredefinedResponseCollection
     */
    private function buildResponseCollection(array $environnements)
    {
        $responseCollection = new PredefinedResponseCollection();

        foreach ($environnements as $framework => $environnement) {
            if (is_array($environnement) === true) {
                $responseCollection->append(new PredefinedResponse($framework, $framework, $framework));
            } else {
                $responseCollection->append(new PredefinedResponse($environnement, $environnement, $environnement));
            }
        }

        return $responseCollection;
    }
}
