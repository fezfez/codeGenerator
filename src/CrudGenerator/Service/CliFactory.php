<?php
namespace CrudGenerator\Service;

use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Command\UpToDateCommand;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;

/**
 * Create CLI instance
 *
 * @author Stéphane Demonchaux
 */
class CliFactory
{
    /**
     * Create CLI instance
     *
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance()
    {
        $cli = new Application;
        $cli->setName('Code Generator Command Line Interface');

        $cli->addCommands(
            array(
                new CreateCommand(),
                new UpToDateCommand(),
            )
        );

        $helperSet = $cli->getHelperSet();
        $helperSet->set(new DialogHelper(), 'dialog');
        $helperSet->set(new FormatterHelper(), 'formatter');

        return $cli;
    }
}
