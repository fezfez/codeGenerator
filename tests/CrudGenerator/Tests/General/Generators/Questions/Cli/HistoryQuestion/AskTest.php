<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Cli\HistoryQuestion;

use CrudGenerator\Generators\Questions\Cli\HistoryQuestion;
use CrudGenerator\History\HistoryCollection;
use CrudGenerator\History\History;
use CrudGenerator\DataObject;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\Generators\GeneratorDataObject;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testRetrieveHistory()
    {
        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');
        $dto = new DataObject();
        $dto->setMetadata($metaData);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDto($dto);

        $HistoryCollection = new HistoryCollection();
        $history           = new History();

        $history->setName('MyEntity')
                ->addDataObject($generatorDTO);

        $HistoryCollection->append($history);

        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context->expects($this->any())
        ->method('log')
        ->will($this->returnValue(''));

        $context->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue($generatorDTO));

        $HistoryStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $HistoryStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue($HistoryCollection));

        $sUT = new HistoryQuestion($HistoryStub, $context);
        $this->assertEquals($generatorDTO, $sUT->ask());
    }

    public function testEmptyHistory()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $context->expects($this->any())
        ->method('log')
        ->will($this->returnValue(''));

        $HistoryCollection = new HistoryCollection();

        $HistoryStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $HistoryStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue($HistoryCollection));

        $sUT = new HistoryQuestion($HistoryStub, $context);
        $this->setExpectedException('CrudGenerator\History\EmptyHistoryException');
        $sUT->ask();
    }
}
