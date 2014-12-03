<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CrudGenerator\Context;

use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\ResponseExpectedException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CliContext implements ContextInterface
{
    /**
     * @var OutputInterface
     */
    private $output = null;

    /**
     * @var QuestionHelper
     */
    private $question = null;

    /**
     * @var InputInterface
     */
    private $input = null;

    /**
     * @var CreateCommand
     */
    private $createCommand = null;

    /**
     * @var array
     */
    private $preResponse = array();

    /**
     * @param QuestionHelper  $question
     * @param OutputInterface $output
     * @param InputInterface  $input
     * @param CreateCommand   $createCommand
     */
    public function __construct(
        QuestionHelper $question,
        OutputInterface $output,
        InputInterface $input,
        CreateCommand $createCommand
    ) {
        $this->question      = $question;
        $this->output        = $output;
        $this->input         = $input;
        $this->createCommand = $createCommand;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
     */
    public function ask(SimpleQuestion $question)
    {
        return $this->question->ask(
            $this->input,
            $this->output,
            new Question(
                sprintf(
                    '<question>Choose a "%s"</question> : default : %s',
                    $question->getText(),
                    $this->getPreResponse($question->getUniqueKey())
                ),
                $this->getPreResponse($question->getUniqueKey())
            )
        );
    }

    /**
     * @param  string $uniqueKey
     * @return string
     */
    private function getPreResponse($uniqueKey)
    {
        $uniqueKey = strtolower($uniqueKey);

        return isset($this->preResponse[$uniqueKey]) === true ? $this->preResponse[$uniqueKey]->get(array()) : null;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function askCollection(QuestionWithPredefinedResponse $questionResponseCollection)
    {
        try {
            // If a preselected response exist, try to retireve response and return her
            return $questionResponseCollection->getPredefinedResponseCollection()
                                              ->offsetGetById($questionResponseCollection->getPreselectedResponse())
                                              ->getResponse();
        } catch (PredefinedResponseException $e) {
            // preselected response does not exist

            $choises = array();

            foreach ($questionResponseCollection->getPredefinedResponseCollection() as $response) {
                $choises[] = $response->getLabel();
            }

            // Ask question
            $choise = $this->question->ask(
                $this->input,
                $this->output,
                new ChoiceQuestion(
                    sprintf(
                        'Choose a "%s" default : %s ',
                        $questionResponseCollection->getText(),
                        $this->getPreResponse($questionResponseCollection->getUniqueKey())
                    ),
                    $choises,
                    $this->getPreResponse($questionResponseCollection->getUniqueKey())
                )
            );

            try {
                return $questionResponseCollection->getPredefinedResponseCollection()
                                                  ->offsetGetByLabel($choise)
                                                  ->getResponse();
            } catch (PredefinedResponseException $e) {
                throw new ResponseExpectedException(
                    sprintf(
                        'Response "%s" does not exist',
                        $choise
                    )
                );
            }
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::menu()
     */
    public function menu($text, $uniqueKey, callable $runner)
    {
        $this->createCommand->create($uniqueKey, $text, $runner);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::confirm()
     */
    public function confirm($text, $uniqueKey)
    {
        return (bool) $this->question->ask(
            $this->input,
            $this->output,
            new ConfirmationQuestion(
                $text . ' y/n'
            )
        );
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::log()
     */
    public function log($text, $name = null)
    {
        if (is_array($text) === true) {
            $tmpArray = array_keys($text);
            foreach ($tmpArray as $value) {
                $this->output->writeln($value);
            }
        } else {
            $this->output->writeln($text);
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::publishGenerator()
     */
    public function publishGenerator(GeneratorDataObject $generator)
    {
        if ($generator->getDto() === null) {
            throw new \Exception('Cannot access to the store');
        }

        $this->preResponse = $generator->getDto()->getStore();
    }
}
