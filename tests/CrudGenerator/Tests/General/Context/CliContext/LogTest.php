<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;

class LogTest extends \PHPUnit_Framework_TestCase
{
    public function testLogIsCorrectlyCall()
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

        $logged = 'test';

        $outputStub->expects($this->once())
                   ->method('writeln')
                   ->with($logged);

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->assertEmpty($sUT->log($logged, 'my_key'));
    }
}
