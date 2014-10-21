<?php
namespace CrudGenerator\Tests\General\Backbone\HistoryBackbone;

use CrudGenerator\Backbone\HistoryBackbone;
use CrudGenerator\History\EmptyHistoryException;
use CrudGenerator\Generators\GeneratorDataObject;

class RunTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $historyQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\History\HistoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $historyQuestion->expects($this->once())
        ->method('ask')
        ->willThrowException(new EmptyHistoryException());

        $context->expects($this->once())
        ->method('log');

        $sUT = new HistoryBackbone($historyQuestion, $context);

        $sUT->run();
    }

    public function testOk()
    {
        $historyQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\History\HistoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $dto = new GeneratorDataObject();

        $historyQuestion->expects($this->once())
        ->method('ask')
        ->willReturn($dto);

        $context->expects($this->once())
        ->method('publishGenerator')
        ->with($dto);

        $sUT = new HistoryBackbone($historyQuestion, $context);

        $sUT->run();
    }
}
