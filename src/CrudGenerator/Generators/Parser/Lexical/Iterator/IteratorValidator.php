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
namespace CrudGenerator\Generators\Parser\Lexical\Iterator;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;

class IteratorValidator
{
    /**
     * @var ConditionValidator
     */
    private $conditionValidator = null;

    /**
     * @param ConditionValidator $conditionValidator
     */
    public function __construct(ConditionValidator $conditionValidator)
    {
        $this->conditionValidator = $conditionValidator;
    }

    /**
     * @param array $node
     * @param GeneratorDataObject $generator
     * @param PhpStringParser $phpStringParser
     * @throws \InvalidArgumentException
     * @return array
     */
    public function retrieveValidIteration(
        array $node,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $overIteratorParser = clone $phpStringParser;
        $validIteration     = array();
        $iterator           = $overIteratorParser->staticPhp($node['iteration']['iterator']);

        if (is_array($iterator) === false && ($iterator instanceof \Traversable) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The result of "%s" is not an instance of Traversable',
                    $node['iteration']['iterator']
                )
            );
        }

        foreach ($iterator as $iteration) {
            $overIteratorParser->addVariable('iteration', $iteration);

            if ($this->conditionValidator->isValid($node['iteration'], $generator, $phpStringParser) === false) {
                continue;
            }

            $validIteration[] = $iteration;
        }

        return $validIteration;
    }
}
