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
namespace CrudGenerator\Generators\Parser\Lexical\QuestionType;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;
use CrudGenerator\Context\SimpleQuestion;

class QuestionTypeSimple implements QuestionTypeInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param array $question
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluateQuestion(
        array $questionRaw,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration,
        array $process
    ) {
        $question = new SimpleQuestion($questionRaw['text'], 'set' . ucfirst($questionRaw['dtoAttribute']));
        $question->setDefaultResponse(
            (isset($questionRaw['defaultResponse']) === true) ? $parser->parse($questionRaw['defaultResponse']) : null
        );
        $question->setRequired($questionRaw['required']);
        $question->setHelpMessage($questionRaw['helpMessage']);
        $question->setResponseType($questionRaw['responseType']);

        $response = $this->context->ask($question);

        if ($response !== null) {
            $questionName = $question['setter'];

            $generator->getDto()->$questionName($response);
        }

        return $generator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::getType()
     */
    public function getType()
    {
        return QuestionTypeEnum::SIMPLE;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeInterface::isIterable()
     */
    public function isIterable(array $question)
    {
        return false;
    }
}
