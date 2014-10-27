<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\SimpleQuestion;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    public function testAskIsCorrectlyCall()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $questionHelperExpects = $questionHelper->expects($this->once());
        $questionHelperExpects->method('ask');
        $questionHelperExpects->willReturn('12');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->assertEquals(
            '12',
            $sUT->ask(new SimpleQuestion('How many banana do your eat per day', 'number_banana'))
        );
    }
}
