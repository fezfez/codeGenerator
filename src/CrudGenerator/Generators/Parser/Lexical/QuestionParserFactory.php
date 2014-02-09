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


use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Questions\DirectoryQuestionFactory;


class QuestionParserFactory
{
    /**
     * @param ContextInterface $context
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\Generators\Parser\Lexical\QuestionParser
     */
    public static function getInstance(ContextInterface $context)
    {
        $dependencyCondition = new DependencyCondition();

        if ($context instanceof CliContext) {
            return new QuestionParser($context, $dependencyCondition, new Cli\QuestionParser($context));
        } elseif ($context instanceof WebContext) {
            return new QuestionParser($context, $dependencyCondition, new Web\QuestionParser());
        } else {
            throw new \InvalidArgumentException('Context "' . get_class($context) . '" not allowed');
        }
    }
}
