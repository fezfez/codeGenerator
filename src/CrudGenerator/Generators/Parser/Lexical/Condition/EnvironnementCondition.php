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

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

class EnvironnementCondition implements ConditionInterface
{
    const NAME = 'environnement';

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $expressions,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        foreach ($expressions as $expression) {
            try {
                $comparaisonDifferentEquals = $this->analyseExpressionType(
                    $expression,
                    ConditionInterface::DIFFERENT_EQUAL
                );
                $addEnvironnementExpression = (
                    $comparaisonDifferentEquals['environnementValue'] !==
                    $generator->getEnvironnement($comparaisonDifferentEquals['environnementName'])
                );
            } catch (\InvalidArgumentException $e) {
                $comparaisonEquals          = $this->analyseExpressionType($expression, ConditionInterface::EQUAL);
                $addEnvironnementExpression = (
                    $comparaisonEquals['environnementValue'] ===
                    $generator->getEnvironnement($comparaisonEquals['environnementName'])
                );
            }

            if ($addEnvironnementExpression === false) {
                return false;
            }
        }

        return true;
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
