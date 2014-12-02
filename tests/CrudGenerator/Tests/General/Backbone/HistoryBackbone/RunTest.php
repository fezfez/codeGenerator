<?php
namespace CrudGenerator\Tests\General\Backbone\HistoryBackbone;

use CrudGenerator\Backbone\HistoryBackbone;
use CrudGenerator\History\EmptyHistoryException;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Tests\TestCase;

class RunTest extends TestCase
{
    public function testWithEmptyHistory()
    {
        $historyQuestion = $this->createMock('CrudGenerator\Generators\Questions\History\HistoryQuestion');
        $context         = $this->createMock('CrudGenerator\Context\CliContext');

        $historyExpects = $historyQuestion->expects($this->once());
        $historyExpects->method('ask');
        $historyExpects->willThrowException(new EmptyHistoryException());

        $sUT = new HistoryBackbone($historyQuestion, $context);

        $this->setExpectedException('CrudGenerator\History\EmptyHistoryException');

        $sUT->run();
    }

    public function testRetrieveHistory()
    {
        $historyQuestion = $this->createMock('CrudGenerator\Generators\Questions\History\HistoryQuestion');
        $context         = $this->createMock('CrudGenerator\Context\CliContext');

        $dto = new GeneratorDataObject();

        $historyExpects = $historyQuestion->expects($this->once());
        $historyExpects->method('ask');
        $historyExpects->willReturn($dto);

        $contextExpects = $context->expects($this->once());
        $contextExpects->method('publishGenerator');
        $contextExpects->with($dto);

        $sUT = new HistoryBackbone($historyQuestion, $context);

        $sUT->run();
    }
}
