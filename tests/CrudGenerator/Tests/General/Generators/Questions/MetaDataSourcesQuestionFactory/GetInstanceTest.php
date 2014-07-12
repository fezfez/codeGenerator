<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetaDataSourcesQuestion;

use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\WebContext;

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
            'CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion',
            MetaDataSourcesQuestionFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion',
            MetaDataSourcesQuestionFactory::getInstance($context)
        );
    }
}
