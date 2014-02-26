<?php
namespace CrudGenerator\Tests\General\Command\RegenerateCommandFactory;

use CrudGenerator\Command\CreateByYamlCommandFactory;

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
            'CrudGenerator\Command\CreateByYamlCommand',
            CreateByYamlCommandFactory::getInstance($dialog, $ConsoleOutputStub)
        );
    }
}
