<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;

class LogTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $logged = 'test';
        $ConsoleOutputStub->expects($this->once())
        ->method('writeln')
        ->with($logged);

        $sUT = new CliContext($dialog, $ConsoleOutputStub);

        $sUT->log($logged, 'my_key');
    }
}
