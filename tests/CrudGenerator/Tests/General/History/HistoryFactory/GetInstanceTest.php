<?php
namespace CrudGenerator\Tests\General\History\HistoryFactory;

use CrudGenerator\History\HistoryFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\History\HistoryManager',
            HistoryFactory::getInstance()
        );
    }
}
