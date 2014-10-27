<?php
namespace CrudGenerator\Tests\General\History\HistoryFactory;

use CrudGenerator\History\HistoryFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $context = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryManager',
            HistoryFactory::getInstance($context)
        );
    }
}
