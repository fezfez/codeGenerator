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

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorParser;

class QuestionParser implements ParserInterface
{
   /**
    * @param array $process
    * @param PhpStringParser $parser
    * @param Generator $generator
    * @param array $questions
    * @return Generator
    */
   public function evaluate(array $process, PhpStringParser $parser, Generator $generator, array $questions)
   {
      foreach ($process['questions'] as $question) {
         if (isset($question['type']) && $question['type'] === GeneratorParser::COMPLEX_QUESTION) {
            $complex = $question['factory']::getInstance();
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
