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
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;

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
                if ($this->conditionValidator->isValid($variable, $generator) === true) {
                    $this->evaluateVariable($variable, $parser, $generator, (bool) $firstIteration);
                }
            }
        }

        return $generator;
    }

    /**
     * @param array $variables
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
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
