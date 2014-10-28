<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;
use CrudGenerator\Tests\TestCase;

class ConfirmTest extends TestCase
{
    public function testConfirmFalse()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $logged = 'test';

        $questionHelperExpects = $questionHelper->expects($this->once());
        $questionHelperExpects->method('ask');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->assertFalse($sUT->confirm($logged, 'my_key'));
    }

    public function testConfirmTrue()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $logged = 'test';

        $questionHelperExpects = $questionHelper->expects($this->once());
        $questionHelperExpects->method('ask');
        $questionHelperExpects->willReturn(true);

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->assertTrue($sUT->confirm($logged, 'my_key'));
    }
}
