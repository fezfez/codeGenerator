<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Installer;

use Composer\Command\Helper\DialogHelper;
use Composer\Command\RequireCommand;
use Composer\Factory;
use Composer\IO\ConsoleIO;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\OutputWeb;
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
     * @param  ContextInterface                                       $context
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
