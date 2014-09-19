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

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\Parser\Lexical\DirectoriesParser;
use CrudGenerator\Generators\Parser\Lexical\FileParser;
use CrudGenerator\Generators\Parser\Lexical\TemplateVariableParser;
use CrudGenerator\Generators\Parser\Lexical\QuestionParser;
use CrudGenerator\Generators\Parser\Lexical\EnvironnementParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollection;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeSimple;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeIterator;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeIteratorWithPredefinedResponse;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeDirectory;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeComplex;
use CrudGenerator\Generators\Parser\Lexical\QuestionAnalyser;
use CrudGenerator\Utils\StaticPhp;
use CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollectionFactory;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;

class ParserCollectionFactory
{
    /**
     * @param ContextInterface $context
     * @throws \InvalidArgumentException
     * @return ParserCollection
     */
    public static function getInstance(ContextInterface $context)
    {
        if ($context instanceof CliContext || $context instanceof WebContext) {
            $fileManager           = new FileManager();
            $collection            = new ParserCollection();
            $environnemetCondition = new EnvironnementCondition();
            $dependencyCondition   = new DependencyCondition();
            $conditionValidation   = new ConditionValidator($dependencyCondition, $environnemetCondition);

            $collection->addPreParse(new EnvironnementParser($context))
                       ->addPostParse(new TemplateVariableParser($conditionValidation))
                       ->addPostParse(new DirectoriesParser())
                       ->addPostParse(new FileParser($fileManager, $conditionValidation))
                       ->addPostParse(
                           new QuestionParser(
                               $context,
                               $conditionValidation,
                               QuestionTypeCollectionFactory::getInstance($context),
                               new QuestionAnalyser()
                           )
                       );

            return $collection;
        } else {
            throw new \InvalidArgumentException('Invalid context');
        }
    }
}
