<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;

class CreateInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

		$sUT = new CliContext($dialog, $ConsoleOutputStub);

		$this->assertInstanceOf(
			'Symfony\Component\Console\Helper\DialogHelper',
			$sUT->getDialogHelper()
		);

		$this->assertInstanceOf(
			'Symfony\Component\Console\Output\ConsoleOutput',
			$sUT->getOutput()
		);
    }
}
