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
namespace CrudGenerator\Generators\Parser\Lexical\Condition;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\ParserInterface;

class EnvironnementCondition implements ParserInterface
{
    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $environnementNode, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        $matches = array();

        foreach ($environnementNode as $environnementNodes) {
             foreach ($environnementNodes as $expression => $toDoIfValidExpression) {
                $matches = array_merge($matches, $this->analyseExpression($expression, $toDoIfValidExpression, $generator));
             }
        }

         return $matches;
    }

    /**
     * @param string $expression
     * @param array $toDoIfValidExpression
     * @param GeneratorDataObject $generator
     * @throws \InvalidArgumentException
     * @return array
     */
    private function analyseExpression($expression, $toDoIfValidExpression, $generator)
    {
        $matches = array();

        try {
            $comparaisonDifferentEquals = $this->analyseExpressionType($expression, GeneratorParser::DIFFERENT_EQUAL);
            $addEnvironnementExpression = ($comparaisonDifferentEquals['environnementValue'] !== $generator->getEnvironnement($comparaisonDifferentEquals['environnementName']));
        } catch (\InvalidArgumentException $e) {
            $comparaisonEquals = $this->analyseExpressionType($expression, GeneratorParser::EQUAL);
            $addEnvironnementExpression = ($comparaisonEquals['environnementValue'] === $generator->getEnvironnement($comparaisonEquals['environnementName']));
        }

        if (true === $addEnvironnementExpression) {
            foreach ($toDoIfValidExpression as $key => $dependency) {
                $matches[] = $dependency;
            }
        }

        return $matches;
    }

    /**
     * @param string $expression
     * @param string $type
     * @throws \InvalidArgumentException
     * @return array
     */
    private function analyseExpressionType($expression, $type)
    {
        $expressionExplode = array_map('trim', explode($type, $expression));

        if (count($expressionExplode) === 2) {
            return array(
                'environnementName'  => $expressionExplode[0],
                'environnementValue' => $expressionExplode[1]
            );
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Unknown expression %s',
                $expression
            )
        );
    }
}
