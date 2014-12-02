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

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;
use CrudGenerator\Utils\PhpStringParser;

class TemplateVariableParser implements ParserInterface
{
    /**
     * @var ConditionValidator
     */
    private $conditionValidator = null;

    /**
     * @param ConditionValidator $conditionValidator
     */
    public function __construct(ConditionValidator $conditionValidator)
    {
        $this->conditionValidator = $conditionValidator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($process['templateVariables']) === true) {
            foreach ($process['templateVariables'] as $variable) {
                if (is_array($variable) === false) {
                    throw new MalformedGeneratorException(
                        sprintf('Variable excepts to be an array "%s" given', gettype($variable))
                    );
                }
                if ($this->conditionValidator->isValid($variable, $generator, $parser) === true) {
                    $this->evaluateVariable($variable, $parser, $generator, (bool) $firstIteration);
                }
            }
        }

        return $generator;
    }

    /**
     * @param  array               $variable
     * @param  PhpStringParser     $parser
     * @param  GeneratorDataObject $generator
     * @param  boolean             $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateVariable(
        array $variable,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
        $variableValue = $parser->parse($variable['value']);
        $parser->addVariable($variable['variableName'], $variableValue);
        $generator->addTemplateVariable($variable['variableName'], $variableValue);

        return $generator;
    }
}
