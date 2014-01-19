<?php

namespace CrudGenerator\Generators;

use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Command\CreateCommandFactory;
use CrudGenerator\Command\GeneratorSandBoxCommandFactory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class GeneratorDependenciesFactory
{
    /**
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     * @return \CrudGenerator\Generators\GeneratorDependencies
     */
    public static function getInstance(DialogHelper $dialog, OutputInterface $output, $strategy = false)
    {
        if ($strategy === false) {
            $command = CreateCommandFactory::getInstance($dialog, $output, $strategy);
        } else {
            $command = GeneratorSandBoxCommandFactory::getInstance($dialog, $output, $strategy);
        }

        return new GeneratorDependencies(
            HistoryFactory::getInstance($dialog, $output),
            $command
        );
    }
}