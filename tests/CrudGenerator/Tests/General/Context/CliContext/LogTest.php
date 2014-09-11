<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;

class LogTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $consoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $questionHelper =  $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $logged = 'test';

        $consoleOutputStub->expects($this->once())
                          ->method('writeln')
                          ->with($logged);

        $sUT = new CliContext($questionHelper, $ConsoleOutputStub);

        $this->assertEmpty($sUT->log($logged, 'my_key'));
    }
}
