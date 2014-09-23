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

class ConditionValidator
{
    /**
     * @var array
     */
    private $conditionCollection = null;

    /**
     * @param array $conditionCollection
     */
    public function __construct(array $conditionCollection)
    {
        $this->conditionCollection = $conditionCollection;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $node,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $isValid = true;

        if (isset($node[ConditionInterface::CONDITION]) === true) {
            $condition = $node[ConditionInterface::CONDITION];

            foreach ($this->conditionCollection as $conditionChecker) {
                if (isset($condition[$conditionChecker::NAME]) === true) {
                    if ($conditionChecker->isValid(
                        $condition[$conditionChecker::NAME],
                        $generator,
                        $phpStringParser
                    ) === false) {
                        $isValid = false;
                    }
                }
            }
        }

        return $isValid;
    }
}
