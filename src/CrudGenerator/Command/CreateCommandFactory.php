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
namespace CrudGenerator\Command;

use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Generators\Questions\MetaDataQuestionFactory;
use CrudGenerator\Generators\Questions\GeneratorQuestionFactory;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Context\CliContext;

/**
 * Generator command
 *
 * @author Stéphane Demonchaux
 */
class CreateCommandFactory
{
    /**
     * @param CliContext $context
     * @return \CrudGenerator\Command\CreateCommand
     */
    public static function getInstance(CliContext $context)
    {
        $historyManager          = HistoryFactory::getInstance($context);
        $metaDataSourcesQuestion = MetaDataSourcesQuestionFactory::getInstance($context);
        $metaDataQuestion        = MetaDataQuestionFactory::getInstance($context);
        $generatorQuestion       = GeneratorQuestionFactory::getInstance($context);
        $parser                  = GeneratorParserFactory::getInstance($context);
        $generatorStrategy       = GeneratorStrategyFactory::getInstance($context);
        $generator               = GeneratorFactory::getInstance($context, $generatorStrategy);

        return new CreateCommand(
            $parser,
        	$generator,
            $historyManager,
            $metaDataSourcesQuestion,
            $metaDataQuestion,
            $generatorQuestion,
        	$context
        );
    }
}
