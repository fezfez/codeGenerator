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

class DependencyCondition
{
    /**
     * @param array $dependencyNode
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param array $questions
     * @param boolean $firstIteration
     * @return array
     */
    public function evaluate(array $dependencyNode, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
        $matches = array();

        foreach ($dependencyNode as $dependencyNodes) {
             foreach ($dependencyNodes as $dependencyName => $dependencyList) {
                 if (substr($dependencyName, 0, 1) === GeneratorParser::DIFFERENT && !in_array(substr($dependencyName, 1), $generator->getDependencies())) {
                     foreach ($dependencyList as $key => $dependency) {
                         $matches[] = $dependency;
                     }
                 } elseif (in_array($dependencyName, $generator->getDependencies())) {
                     foreach ($dependencyList as $key => $dependency) {
                         $matches[] = $dependency;
                     }
                 }
             }
         }

         return $matches;
    }
}
