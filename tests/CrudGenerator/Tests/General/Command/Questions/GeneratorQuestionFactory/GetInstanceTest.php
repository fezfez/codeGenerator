<?php
namespace CrudGenerator\Tests\General\Command\Questions\GeneratorQuestion;

use CrudGenerator\Command\Questions\GeneratorQuestionFactory;

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
            'CrudGenerator\Command\Questions\GeneratorQuestion',
            GeneratorQuestionFactory::getInstance($dialog, $ConsoleOutputStub)
        );
    }

    public function testInstanceStub()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Command\Questions\GeneratorQuestion',
            GeneratorQuestionFactory::getInstance($dialog, $ConsoleOutputStub, true)
        );
    }
}