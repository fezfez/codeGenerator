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
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Generator;

class QuestionResponseParser implements ParserInterface
{
   /**
    * @param array $process
    * @param PhpStringParser $parser
    * @param Generator $generator
    * @param array $questions
    * @return Generator
    */
    public function evaluate(array $process, PhpStringParser $parser, Generator $generator, array $questions)
    {
         foreach ($questions as $questionName => $questionReponse) {
             if (is_array($questionReponse)) {
                 foreach ($questionReponse as $firstArgument => $secondArgument) {
                     if (method_exists($generator->getDTO(), $questionName)) {
                         $generator->getDTO()->$questionName($firstArgument, $secondArgument);
                     }
                 }
             } else {
                 if (method_exists($generator->getDTO(), $questionName)) {
                     $generator->getDTO()->$questionName($questionReponse);
                 }
             }
         }

        return $generator;
    }
}