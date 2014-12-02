<?php
namespace CrudGenerator\Tests\General\History\History;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\History\History;

class HistoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $generator = new GeneratorDataObject();
        $history   = new History();

        $history->addDataObject($generator)
                ->setName('name');

        $this->assertEquals(
            array($generator),
            $history->getDataObjects()
        );

        $this->assertEquals(
            'name',
            $history->getName()
        );
    }
}
