<?php
namespace CrudGenerator\Tests\General\History\HistoryFactory;

use CrudGenerator\History\HistoryFactory;

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


        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryManager',
            HistoryFactory::getInstance($dialog, $ConsoleOutputStub)
        );
    }
}
