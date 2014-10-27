<?php
namespace CrudGenerator\Tests\General\History\HistoryHydratorFactory;

use CrudGenerator\History\HistoryHydratorFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $context = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryHydrator',
            HistoryHydratorFactory::getInstance($context)
        );
    }
}
