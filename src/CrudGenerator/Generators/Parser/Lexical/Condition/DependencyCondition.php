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

class DependencyCondition implements ParserInterface
{
    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(
        array $dependencyNode,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
        $matches               = array();
        $generatorDependencies = array();

        foreach ($generator->getDependencies() as $dependency) {
            $generatorDependencies[] = $dependency->getName();
        }

        foreach ($dependencyNode as $dependencyNodes) {
            foreach ($dependencyNodes as $dependencyName => $dependencyList) {
                  $matches = $this->checkDifferentCondition(
                      $matches,
                      $generatorDependencies,
                      $dependencyList,
                      $dependencyName
                  );
                  $matches = $this->checkEqualCondition(
                      $matches,
                      $generatorDependencies,
                      $dependencyList,
                      $dependencyName
                  );
             }
         }

         return $matches;
    }

    /**
     * @param array $matches
     * @param array $generatorDependencies
     * @param array $dependencyList
     * @param string $dependencyName
     * @return array
     */
    private function checkDifferentCondition(
        array $matches,
        array $generatorDependencies,
        array $dependencyList,
        $dependencyName
    ) {
        if (substr($dependencyName, -strlen(GeneratorParser::UNDEFINED)) === GeneratorParser::UNDEFINED &&
            false === in_array(substr($dependencyName, 0, -count(GeneratorParser::UNDEFINED)), $generatorDependencies)
        ) {
            foreach ($dependencyList as $dependency) {
                $matches[] = $dependency;
            }
        }

        return $matches;
    }

    /**
     * @param array $matches
     * @param array $generatorDependencies
     * @param array $dependencyList
     * @param string $dependencyName
     * @return array
     */
    private function checkEqualCondition(
        array $matches,
        array $generatorDependencies,
        array $dependencyList,
        $dependencyName
    ) {
        if (in_array($dependencyName, $generatorDependencies) === true) {
            foreach ($dependencyList as $dependency) {
                $matches[] = $dependency;
            }
        }

        return $matches;
    }
}
