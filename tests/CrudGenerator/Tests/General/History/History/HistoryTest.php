<?php
namespace CrudGenerator\Tests\General\History\History;

use CrudGenerator\History\History;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class HistoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $dataObject = new Architect();
        $history = new History();

        $history->setDataObject($dataObject)
                ->setName('name');

        $this->assertInstanceOf(
            'CrudGenerator\Generators\ArchitectGenerator\Architect',
            $history->getDataObject()
        );

        $this->assertEquals(
            'name',
            $history->getName()
        );
    }
}
