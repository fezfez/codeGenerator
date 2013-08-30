<?php
namespace CrudGenerator\Tests\General\Command\Questions\MetaDataQuestion;

use CrudGenerator\Command\Questions\MetaDataQuestionFactory;

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
            'CrudGenerator\Command\Questions\MetaDataQuestion',
            MetaDataQuestionFactory::getInstance($dialog, $ConsoleOutputStub)
        );
    }
}
