<?php
namespace CrudGenerator\Tests\General\Command\Questions\History\HistoryQuestionFactory;

use CrudGenerator\Generators\Questions\History\HistoryQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\History\HistoryQuestion',
            HistoryQuestionFactory::getInstance($context)
        );
    }
}
