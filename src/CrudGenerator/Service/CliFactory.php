<?php
namespace CrudGenerator\Service;

use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Command\UpToDateCommand;
use CrudGenerator\Doctrine\Helper\ServiceManagerHelper;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;

class CliFactory
{
    /**
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance()
    {
        $cli = new Application;
        $cli->setName('Code Generator Command Line Interface');;

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
