<?php
namespace CrudGenerator\Tests\General\History\HistoryHydratorFactory;

use CrudGenerator\History\HistoryHydratorFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryHydrator',
            HistoryHydratorFactory::getInstance($context)
        );
    }
}
