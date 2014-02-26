<?php
namespace CrudGenerator\Tests\General\Command\CreateByconfigCommandFactory;

use CrudGenerator\Command\CreateByConfigCommandFactory;

class GetInstanceREWRITE extends \PHPUnit_Framework_TestCase
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
            'CrudGenerator\Command\CreateByConfigCommand',
            CreateByConfigCommandFactory::getInstance($dialog, $ConsoleOutputStub)
        );
    }
}
