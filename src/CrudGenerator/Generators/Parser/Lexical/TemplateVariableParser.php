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

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class TemplateVariableParser implements ParserInterface
{
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var EnvironnementCondition
     */
    private $environnementCondition = null;
    /**
     * @var DependencyCondition
     */
    private $dependencyCondition = null;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager, EnvironnementCondition $environnementCondition, DependencyCondition $dependencyCondition)
    {
        $this->fileManager            = $fileManager;
        $this->environnementCondition = $environnementCondition;
        $this->dependencyCondition    = $dependencyCondition;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
    	if (isset($process['templateVariables'])) {
	        foreach ($process['templateVariables'] as $variables) {
	        	if (!is_array($variables)) {
	        		throw new MalformedGeneratorException('Variable excepts to be an array "' . gettype($variables) . "' given");
	        	}
				$this->evaluateVariable($variables, $parser, $generator, $questions, $firstIteration);
	        }
    	}

        return $generator;
    }

    /**
     * @param array $variables
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param array $questions
     * @param unknown $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateVariable(array $variables, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
    	foreach ($variables as $varName => $value) {
    		if ($varName === GeneratorParser::DEPENDENCY_CONDITION) {
    			$matches = $this->dependencyCondition->evaluate($value, $parser, $generator, $questions, $firstIteration);
    			foreach ($matches as $matchesDependency) {
    				$generator = $this->evaluateVariable($matchesDependency, $parser, $generator, $questions, $firstIteration);
    			}
    		} elseif ($varName === GeneratorParser::ENVIRONNEMENT_CONDITION) {
    			$matches = $this->environnementCondition->evaluate($value, $parser, $generator, $questions, $firstIteration);
    		    foreach ($matches as $matchesEnvironnement) {
    				$generator = $this->evaluateVariable($matchesEnvironnement, $parser, $generator, $questions, $firstIteration);
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