<?php
namespace CrudGenerator\Tests\General\History\History;

use CrudGenerator\History\History;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class HistoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $dataObject = new Architect();
        $history = new History();

        $history->addDataObject($dataObject)
                ->setName('name');

        $this->assertEquals(
            array($dataObject),
            $history->getDataObjects()
        );

        $this->assertEquals(
            'name',
            $history->getName()
        );
    }
}
