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
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\CliContext;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class SandBoxStrategyFactory
{
    /**
     * @param ContextInterface $context
     * @param InputArgument $input
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\Generators\Strategies\SandBoxStrategy
     */
    public static function getInstance(ContextInterface $context, ArrayInput $input = null)
    {
        if ($context instanceof CliContext) {
            $view   = ViewFactory::getInstance();

            if ($input !== null) {
                $filter = $input->getArgument('filter');
            } else {
                $filter = null;
            }

            return new SandBoxStrategy($view, $context, $filter);
        } else {
            throw new \InvalidArgumentException('Context "' . get_class($context) . '" not allowed');
        }
    }
}