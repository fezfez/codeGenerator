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

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class QuestionAnalyser
{
    /**
     * @param array $question
     * @throws MalformedGeneratorException
     * @return array
     */
    public function checkIntegrity(array $question)
    {
        if (isset($question['text']) === false) {
            $this->throwException($question, 'text');
        }
        if (isset($question['dtoAttribute']) === false) {
            $this->throwException($question, 'dtoAttribute');
        }
        $question['setter'] = 'set' . ucfirst($question['dtoAttribute']);

        if (isset($question['required']) === false) {
            $question['required'] = false;
        }
        if (isset($question['helpMessage']) === false) {
            $question['helpMessage'] = null;
        }

        if (isset($question['responseType']) === false) {
            $responseType = new QuestionResponseTypeEnum();
        } else {
            try {
                $responseType = new QuestionResponseTypeEnum($question['responseType']);
            } catch (\UnexpectedValueException $e) {
                throw new MalformedGeneratorException($e->getMessage());
            }
        }
        $question['responseType'] = $responseType;

        if (isset($question['type']) === false) {
            $type = new QuestionTypeEnum();
        } else {
            try {
                $type = new QuestionTypeEnum($question['type']);
            } catch (\UnexpectedValueException $e) {
                throw new MalformedGeneratorException($e->getMessage());
            }
        }
        $question['type'] = $type;

        if ($question['type']->is(QuestionTypeEnum::COMPLEX)) {
            if (isset($question['factory']) === false) {
                $this->throwException($question, 'factory');
            }
        } elseif ($question['type']->is(QuestionTypeEnum::ITERATOR) ||
                $question['type']->is(QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE)
        ) {
            if (isset($question['collection']) === false) {
                $this->throwException($question, 'collection');
            }

            if (isset($question['origine']) === false) {
                $this->throwException($question, 'origine');
            }

            if ($question['type']->is(QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE) === true) {
                if (isset($question['predefinedResponse']) === false) {
                    $this->throwException($question, 'predefinedResponse');
                }
                if (is_array($question['predefinedResponse']) === false) {
                    throw new MalformedGeneratorException(
                        sprintf('"predefinedResponse" must be an array in %s', json_encode($question))
                    );
                }
            }
        }

        return $question;
    }

    /**
     * @param string $question
     * @param string $missingAttr
     * @throws MalformedGeneratorException
     */
    private function throwException($question, $missingAttr)
    {
        throw new MalformedGeneratorException(
            sprintf(
                'Question "%s" does not have property "%s"',
                json_encode($question),
                $missingAttr
            )
        );
    }
}
