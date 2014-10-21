<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\SimpleQuestion;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testAskIsCorrectlyCall()
    {
        $outputStub = $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $inputStub = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
        ->disableOriginalConstructor()
        ->getMock();

        $questionHelper = $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $createCommandMock = $this->getMockBuilder('CrudGenerator\Command\CreateCommand')
        ->disableOriginalConstructor()
        ->getMock();

        $questionHelper->expects($this->once())
                   ->method('ask')
                   ->willReturn('12');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->assertEquals(
            '12',
            $sUT->ask(new SimpleQuestion('How many banana do your eat per day', 'number_banana'))
        );
    }
}
