<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetaDataQuestion;

use CrudGenerator\Generators\Questions\MetaDataQuestionFactory;
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
            'CrudGenerator\Generators\Questions\Cli\MetaDataQuestion',
            MetaDataQuestionFactory::getInstance($context)
        );
    }
}