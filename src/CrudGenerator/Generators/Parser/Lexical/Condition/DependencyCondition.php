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

class DependencyCondition implements ConditionInterface
{
    const NAME = 'dependency';

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function isValid(
        array $nodes,
        GeneratorDataObject $generator,
        PhpStringParser $phpStringParser
    ) {
        $dependencies = array();

        foreach ($generator->getDependencies() as $dependency) {
            $dependencies[] = $dependency->getName();
        }

        foreach ($nodes as $node) {
            if (($this->differentCondition($dependencies, $node) ||
                $this->equalCondition($dependencies, $node)) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $generatorDependencies
     * @param array $dependencyList
     * @param string $dependencyName
     * @return array
     */
    private function differentCondition(array $generatorDependencies, $dependencyName)
    {
        $isTrue = false;

        if (substr($dependencyName, -strlen(ConditionInterface::UNDEFINED)) === ConditionInterface::UNDEFINED &&
            false === in_array(
                substr($dependencyName, 0, -count(ConditionInterface::UNDEFINED)),
                $generatorDependencies
            )
        ) {
            $isTrue = true;
        }

        return $isTrue;
    }

    /**
     * @param array $generatorDependencies
     * @param array $dependencyList
     * @param string $dependencyName
     * @return array
     */
    private function equalCondition(array $generatorDependencies, $dependencyName)
    {
        $isTrue = false;

        if (in_array($dependencyName, $generatorDependencies) === true) {
            $isTrue = true;
        }

        return $isTrue;
    }
}
