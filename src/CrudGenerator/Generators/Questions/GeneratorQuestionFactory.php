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
namespace CrudGenerator\Generators\Questions;

use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

class GeneratorQuestionFactory
{
    /**
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     * @param boolean $stub
     * @return \CrudGenerator\Command\Questions\GeneratorQuestion
     */
    public static function getInstance(ContextInterface $context, $input = null, $stub = false)
    {
        $generatorFinder = GeneratorFinderFactory::getInstance();

        if ($context instanceof CliContext) {
            return new Cli\GeneratorQuestion($generatorFinder, $context->getOutput(), $context->getDialogHelper());
        } elseif ($context instanceof WebContext) {
        	return new Web\GeneratorQuestion($generatorFinder);
        } else {
        	throw new \InvalidArgumentException('Invalid context');
        }
    }
}
