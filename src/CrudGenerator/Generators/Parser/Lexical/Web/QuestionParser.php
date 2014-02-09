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
use CrudGenerator\Generators\Parser\Lexical\QuestionInterface;
use CrudGenerator\Generators\Questions\Web\DirectoryQuestion;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class QuestionParser implements QuestionInterface
{
    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param array $questions
     * @param unknown $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluateQuestions(array $question, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
        $generator->addQuestion(
            array(
                'dtoAttribute'    => 'set' . ucfirst($question['dtoAttribute']),
                'text'            => $question['text'],
                'value'           => (isset($questions['set' . ucfirst($question['dtoAttribute'])])) ? $questions['set' . ucfirst($question['dtoAttribute'])] : '',
                'defaultResponse' => (isset($question['defaultResponse'])) ? $parser->parse($question['defaultResponse']) : null
            )
        );

        return $generator;
    }
}
