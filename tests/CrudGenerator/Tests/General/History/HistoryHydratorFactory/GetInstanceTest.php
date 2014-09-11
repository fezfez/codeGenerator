<?php
namespace CrudGenerator\Tests\General\History\HistoryHydratorFactory;

use CrudGenerator\History\HistoryHydratorFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryHydrator',
            HistoryHydratorFactory::getInstance($context)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        HistoryHydratorFactory::getInstance($context);
    }
}
