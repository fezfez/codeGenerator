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
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class EnvironnementParser implements ParserInterface
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

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
     public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
     {
         if (isset($process['environnement']) && is_array($process['environnement'])) {
            foreach ($process['environnement'] as $environnementName => $question) {
                if (!is_array($question)) {
                    throw new MalformedGeneratorException('Questions excepts to be an array "' . gettype($question) . "' given");
                }

                $generator = $this->evaluateQuestions($environnementName, $question, $parser, $generator, $firstIteration);
            }
         }

        return $generator;
    }

    /**
     * @param string $environnementName
     * @param array $environnements
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    private function evaluateQuestions($environnementName, array $environnements, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        $possibleValues = array();
        $toRecurse      = array();
        foreach ($environnements as $framework => $environnement) {
            if (is_array($environnement)) {
                $possibleValues[]      = array('label' => $framework, 'id' => $framework);
                $toRecurse[$framework] = $environnement;
            } else {
                $possibleValues[] = array('label' => $environnement, 'id' => $environnement);
            }
        }

        $response = $this->context->askCollection(
            $environnementName . ' environnement',
            'environnement_' . $environnementName,
            $possibleValues
        );

        $generator->addQuestion(
            array(
                'dtoAttribute'    => 'environnement_' . $environnementName,
                'text'            => $environnementName . ' environnement',
                'type'            => 'select',
                'values'          => $possibleValues,
                'value'           => $response,
                'placeholder'     => $response,
                'required'        => true
            )
        );

        if ($response !== null && isset($toRecurse[$response])) {
            $this->evaluateQuestions($response, $toRecurse[$response], $parser, $generator, $firstIteration);
        }

        return $generator;
    }
}
