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
namespace CrudGenerator\Generators\Strategies;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\FileConflict\FileConflictManagerFactory;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\CliContext;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class GeneratorStrategyFactory
{
    /**
     * @param ContextInterface $context
     * @throws \Exception
     * @return \CrudGenerator\Generators\Strategies\GeneratorStrategy
     */
    public static function getInstance(ContextInterface $context)
    {
    	if ($context instanceof CliContext) {
	        $view               = ViewFactory::getInstance();
	        $fileManager        = new FileManager();
	        $fileConflitManager = FileConflictManagerFactory::getInstance($context->getOutput(), $context->getDialogHelper());

	        return new GeneratorStrategy($view, $context->getOutput(), $fileManager, $fileConflitManager);
    	} else {
			throw new \Exception('Web not supported');
    	}
    }
}