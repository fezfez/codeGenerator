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
namespace CrudGenerator\Generators\Installer;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\OutputWeb;
use Composer\Command\RequireCommand;
use Composer\Command\Helper\DialogHelper;
use Composer\IO\ConsoleIO;
use Composer\Factory;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;

/**
 *
 * @author Stéphane Demonchaux
 */
class GeneratorInstallerFactory
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\Generators\Installer\GeneratorInstaller
     */
    public static function getInstance(ContextInterface $context)
    {
        $requireCommand = new RequireCommand();
        $requireCommand->ignoreValidationErrors();

        $definition = $requireCommand->getDefinition();
        $definition->addOption(new InputOption('verbose'));
        $input = new ArrayInput(array(), $definition);
        $input->setInteractive(false);

        $dialog = new DialogHelper();
        $dialog->setInput($input);

        $output = new OutputWeb($context);
        $output->setVerbosity(4);
        $helper = new HelperSet(array($dialog, new ProgressHelper()));
        $io     = new ConsoleIO($input, $output, $helper);
        $io->enableDebugging(time());

        $composer = Factory::create($io, null, true);
        $requireCommand->setIO($io);
        $requireCommand->setComposer($composer);
        $requireCommand->setHelperSet($helper);

        return new GeneratorInstaller(
            $input,
            $requireCommand,
            $output
        );
    }
}
