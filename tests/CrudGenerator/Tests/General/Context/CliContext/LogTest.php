<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;
use CrudGenerator\Tests\TestCase;

class LogTest extends TestCase
{
    public function testLogIsCorrectlyCall()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $logged = 'test';

        $outputStubExpects = $outputStub->expects($this->once());
        $outputStubExpects->method('writeln');
        $outputStubExpects->with($logged);

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->assertEmpty($sUT->log($logged, 'my_key'));
    }
}
