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
namespace CrudGenerator\Generators\Parser\Lexical\Web;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Generators\Questions\Web\DirectoryQuestion;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class EnvironnementParser implements ParserInterface
{
    /**
     * @var WebContext
     */
    private $webContext = null;

    /**
     * @param WebContext $webContext
     * @param DirectoryQuestion $directoryQuestion
     * @param DependencyCondition $dependencyCondition
     */
    public function __construct(WebContext $webContext)
    {
        $this->webContext          = $webContext;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
     public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
     {
         if (isset($process['environnement'])) {
	        foreach ($process['environnement'] as $question) {
	        	if (!is_array($question)) {
					throw new MalformedGeneratorException('Questions excepts to be an array "' . gettype($question) . "' given");
	        	}

	            $generator = $this->evaluateQuestions($question, $parser, $generator, $questions, $firstIteration);
	        }
         }

        return $generator;
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param array $questions
     * @param unknown $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateQuestions(array $environnements, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
    	foreach ($environnements as $framework => $environnement) {
    		$generator->addEnvironnement();
    	    foreach ($environnement['backend'] as $backend) {

    		}
    		foreach ($environnement['template'] as $backend) {

    		}
    	}

        return $generator;
    }
}
