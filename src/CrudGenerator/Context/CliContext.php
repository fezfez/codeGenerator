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

namespace CrudGenerator\Context;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Generators\ResponseExpectedException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;

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
     * @param QuestionHelper $question
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param CreateCommand $createCommand
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
                sprintf('<question>Choose a "%s"</question> : ', $question->getText())
            )
        );
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function askCollection(QuestionWithPredefinedResponse $questionResponseCollection)
    {
        $preselectedResponse = $questionResponseCollection->getPreselectedResponse();

        // If a preselected response exist, try to retireve response and return her
        if ($preselectedResponse !== null) {
            try {
                return $questionResponseCollection->getPredefinedResponseCollection()
                                                  ->offsetGetById($preselectedResponse)
                                                  ->getResponse();
            } catch (PredefinedResponseException $e) {
                // preselected response does not exist anymore
            }
        }

        $choises = array();

        foreach ($questionResponseCollection->getPredefinedResponseCollection() as $response) {
            $choises[] = $response->getLabel();
        }

        // Ask question
        $choise = $this->question->ask(
            $this->input,
            $this->output,
            new ChoiceQuestion(
                sprintf('Choose a "%s"', $questionResponseCollection->getText()) ,
                $choises
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
        return $this->question->ask(
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
        if (is_array($text)) {
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

    }
}
