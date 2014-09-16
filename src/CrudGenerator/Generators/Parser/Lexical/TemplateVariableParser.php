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

class TemplateVariableParser implements ParserInterface
{
    /**
     * @var EnvironnementCondition
     */
    private $environnementCondition = null;
    /**
     * @var DependencyCondition
     */
    private $dependencyCondition = null;

    /**
     * @param EnvironnementCondition $environnementCondition
     * @param DependencyCondition $dependencyCondition
     */
    public function __construct(
        EnvironnementCondition $environnementCondition,
        DependencyCondition $dependencyCondition
    ) {
        $this->environnementCondition = $environnementCondition;
        $this->dependencyCondition    = $dependencyCondition;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($process['templateVariables']) === true) {
            foreach ($process['templateVariables'] as $variables) {
                if (is_array($variables) === false) {
                    throw new MalformedGeneratorException(
                        sprintf('Variable excepts to be an array "%s" given', gettype($variables))
                    );
                }
                $this->evaluateVariable($variables, $parser, $generator, (bool) $firstIteration);
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
        array $variables,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
        foreach ($variables as $varName => $value) {
            if ($varName === GeneratorParser::DEPENDENCY_CONDITION) {
                $matches = $this->dependencyCondition->evaluate($value, $parser, $generator, $firstIteration);
                foreach ($matches as $matchesDependency) {
                    $generator = $this->evaluateVariable($matchesDependency, $parser, $generator, $firstIteration);
                }
            } elseif ($varName === GeneratorParser::ENVIRONNEMENT_CONDITION) {
                $matches = $this->environnementCondition->evaluate($value, $parser, $generator, $firstIteration);
                foreach ($matches as $matchesEnvironnement) {
                    $generator = $this->evaluateVariable($matchesEnvironnement, $parser, $generator, $firstIteration);
                }
            } else {
                $variableValue = $parser->parse($value);
                $parser->addVariable($varName, $variableValue);
                $generator->addTemplateVariable($varName, $variableValue);
            }
        }

        return $generator;
    }
}
