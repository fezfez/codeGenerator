<?php
namespace CrudGenerator\Tests\General\Command\Questions\HistoryQuestionFactory;

use CrudGenerator\Generators\Questions\HistoryQuestionFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Cli\HistoryQuestion',
            HistoryQuestionFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Cli\HistoryQuestion',
            HistoryQuestionFactory::getInstance($context)
        );
    }
}
