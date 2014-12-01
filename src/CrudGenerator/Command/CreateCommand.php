<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Command;

use Symfony\Component\Console\Application;

/**
 * Generator command
 *
 * @author Stéphane Demonchaux
 */
class CreateCommand
{
    /**
     * @var Application
     */
    private $application = null;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string   $action
     * @param string   $definition
     * @param callable $runner
     */
    public function create($action, $definition, callable $runner)
    {
        $commandDefinition = new CommandDefinition();
        $commandDefinition->setAction($action)
                          ->setDefinition($definition)
                          ->setNamespace('generator')
                          ->setRunner($runner);

        $this->application->add(new SkeletonCommand($commandDefinition));
    }
}
