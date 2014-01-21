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
use CrudGenerator\Context\WebContext;

class QuestionParser implements ParserInterface
{
	/**
	 * @var WebContext
	 */
	private $webContext = null;

	/**
	 * @param CliContext $cliContext
	 */
	public function __construct(WebContext $cliContext)
	{
		$this->webContext = $cliContext;
	}

   /**
    * @param array $process
    * @param PhpStringParser $parser
    * @param Generator $generator
    * @param array $questions
    * @return Generator
    */
   public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, array $questions)
   {
      foreach ($process['questions'] as $question) {
         if (isset($question['type']) && $question['type'] === GeneratorParser::COMPLEX_QUESTION) {
            $complex = $question['factory']::getInstance($this->webContext);
            $generator = $complex->ask($generator);
         } else {
            $generator->addQuestion(
                  array(
                        'dtoAttribute'    => 'set' . ucfirst($question['dtoAttribute']),
                        'text'            => $question['text'],
                        'value'           => (isset($questions['set' . ucfirst($question['dtoAttribute'])])) ? $questions['set' . ucfirst($question['dtoAttribute'])] : '',
                        'defaultResponse' => (isset($question['defaultResponse']) && $parser->issetVariable($question['defaultResponse'])) ? $parser->parse($question['defaultResponse']) : ''
                  )
            );
         }
      }

      return $generator;
   }
}
