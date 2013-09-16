<?php

namespace CrudGenerator\Generators;

use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Command\CreateCommandFactory;

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
        return new GeneratorDependencies(
            HistoryFactory::getInstance($dialog, $output),
            CreateCommandFactory::getInstance($dialog, $output, $strategy)
        );
    }
}