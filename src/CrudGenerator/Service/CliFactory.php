<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Service;

use CrudGenerator\Command\CreateCommandFactory;
use CrudGenerator\Context\CliContext;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use CrudGenerator\Backbone\MainBackboneFactory;
use Symfony\Component\Console\Input\InputInterface;

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
     * @param  OutputInterface                        $output
     * @param  InputInterface                         $input
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance(OutputInterface $output, InputInterface $input)
    {
        $questionHelper = new QuestionHelper();
        $application    = new Application('Code Generator Command Line Interface', 'Alpha');
        $application->getHelperSet()->set(new FormatterHelper(), 'formatter');
        $application->getHelperSet()->set($questionHelper, 'question');

        $context = new CliContext(
            $questionHelper,
            $output,
            $input,
            CreateCommandFactory::getInstance($application)
        );

        $mainBackbone = MainBackboneFactory::getInstance($context);

        $mainBackbone->run();

        return $application;
    }
}
