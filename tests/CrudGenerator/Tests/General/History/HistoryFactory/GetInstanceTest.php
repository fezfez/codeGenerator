<?php
namespace CrudGenerator\Tests\General\History\HistoryFactory;

use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryManager',
            HistoryFactory::getInstance($context)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        HistoryFactory::getInstance($context);
    }
}
