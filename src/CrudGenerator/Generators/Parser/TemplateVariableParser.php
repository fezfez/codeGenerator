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
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorParser;

class TemplateVariableParser implements ParserInterface
{
	/**
	 * @var FileManager
	 */
	private $fileManager = null;

	/**
	 * @param FileManager $fileManager
	 */
	public function __construct(FileManager $fileManager)
	{
		$this->fileManager = $fileManager;
	}

   /**
    * @param array $process
    * @param PhpStringParser $parser
    * @param Generator $generator
    * @param array $questions
    * @return Generator
    */
    public function evaluate(array $process, PhpStringParser $parser, Generator $generator, array $questions)
    {
        $templateVariable = array();

        foreach ($process['templateVariables'] as $variables) {
            foreach ($variables as $varName => $value) {
                if($varName === GeneratorParser::ENVIRONNEMENT_CONDITION) {
                	$generator =  $this->evaluateEnvironnementCondition($value, $parser, $generator);
                } else {
                    $variableValue = $parser->parse($value);
                    $parser->addVariable($varName, $variableValue);
                    $generator->addTemplateVariable($varName, $variableValue);
                }
            }
        }

        return $generator;
    }

    /**
     * @param array $environnementNode
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @return Generator
     */
    private function evaluateEnvironnementCondition(array $environnementNode, PhpStringParser $parser, Generator $generator)
    {
        foreach ($environnementNode as $environements) {
            foreach ($environements as $environment => $environmentVariables) {
                foreach ($environmentVariables as $environmentVariable => $environmentVariablesValue) {
                    if ($environment === 'zf2') {
                        try {
                            \CrudGenerator\EnvironnementResolver\ZendFramework2Environnement::getDependence($this->fileManager);
                            $variableValue = $parser->parse($environmentVariablesValue);
                            $parser->addVariable($environmentVariable, $variableValue);
                            $generator->addTemplateVariable($environmentVariable, $variableValue);
                        } catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
                        }
                    } elseif ($environment === GeneratorParser::CONDITION_ELSE) {
                        $variableValue = $parser->parse($environmentVariablesValue);
                        $parser->addVariable($environmentVariable, $variableValue);
                        $generator->addTemplateVariable($environmentVariable, $variableValue);
                    }
                }
            }
        }

        return $generator;
    }
}
