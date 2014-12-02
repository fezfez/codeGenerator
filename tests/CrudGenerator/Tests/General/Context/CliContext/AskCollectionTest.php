<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Tests\TestCase;

class AskCollectionTest extends TestCase
{
    public function testWithPreselectedResponse()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $questionHelperExpects = $questionHelper->expects($this->never());
        $questionHelperExpects->method('ask');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $response           = new PredefinedResponse('banana_10', '10 banana', 10);
        $responseCollection = new PredefinedResponseCollection();

        $responseCollection->append($response);

        $question = new QuestionWithPredefinedResponse(
            'How many banana do your eat per day',
            'number_banana',
            $responseCollection
        );

        $question->setPreselectedResponse('banana_10');

        $this->assertEquals(
            10,
            $sUT->askCollection($question)
        );
    }

    public function testWithoutPreselectedResponse()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $questionHelperExpects = $questionHelper->expects($this->once());
        $questionHelperExpects->method('ask');
        $questionHelperExpects->willReturn('10 banana');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $response           = new PredefinedResponse('banana_10', '10 banana', 10);
        $responseCollection = new PredefinedResponseCollection();

        $responseCollection->append($response);

        $question = new QuestionWithPredefinedResponse(
            'How many banana do your eat per day',
            'number_banana',
            $responseCollection
        );

        $this->assertEquals(
            10,
            $sUT->askCollection($question)
        );
    }
}
