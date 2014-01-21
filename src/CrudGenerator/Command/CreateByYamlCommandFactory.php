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

use CrudGenerator\Command\CreateByYamlCommand;
use CrudGenerator\Generators\Questions\HistoryQuestionFactory;
use CrudGenerator\Context\CliContext;

/**
 * Regenerate command factory
 *
 * @author Stéphane Demonchaux
 */
class CreateByYamlCommandFactory
{
    /**
     * @param CliContext $context
     * @return \CrudGenerator\Command\CreateByYamlCommand
     */
    public static function getInstance(CliContext $context)
    {
        $historyQuestion      = HistoryQuestionFactory::getInstance($context);
        //$codeGeneratorFactory = new CodeGeneratorFactory(GeneratorStrategyFactory::getInstance($context->getOutput(), $context->getDialogHelper()));

        return new CreateByYamlCommand($context->getDialogHelper(), $context->getOutput(), $historyQuestion, $codeGeneratorFactory);
    }
}
