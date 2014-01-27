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
    public function evaluate(array $environnementNode, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
        $matches = array();

        foreach ($environnementNode as $environements) {
             foreach ($environements as $environment => $environmentTemplates) {
                 foreach ($environmentTemplates as $environmentTemplateName => $environmentTragetFile) {
                     if ($environment[0] === GeneratorParser::DIFFERENT) {
                        if (!in_array(substr($environment, 1), $generator->getDependencies())) {
                            if (is_array($environmentTragetFile) && array_diff_key($environmentTragetFile,array_keys(array_keys($environmentTragetFile)))) {
                                $matches[] = $environmentTragetFile;
                            } else {
                                foreach ($environmentTragetFile as $key => $file) {
                                    $matches[] = array($key => $file);
                                }
                            }
                        }
                     } else {
                         if (in_array($environment, $generator->getDependencies())) {
                             if (is_array($environmentTragetFile) && array_diff_key($environmentTragetFile,array_keys(array_keys($environmentTragetFile)))) {
                                 $matches[] = $environmentTragetFile;
                             } else {
                                foreach ($environmentTragetFile as $key => $file) {
                                    $matches[$key] = array($key => $file);
                                }
                             }
                         }
                     }
                 }
             }
         }

         return $matches;
    }
}
