<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Cli\HistoryQuestion;

use CrudGenerator\Generators\Questions\Cli\HistoryQuestion;
use CrudGenerator\History\HistoryCollection;
use CrudGenerator\History\History;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;


class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
    	->disableOriginalConstructor()
    	->getMock();
        $context->expects($this->any())
        ->method('log')
        ->will($this->returnValue(''));
        $context->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue(0));

        $metaData = new MetadataDataObjectDoctrine2(
        	new MetaDataColumnCollection(),
        	new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');
        $dto = new Architect();
        $dto->setMetadata($metaData);

        $HistoryCollection = new HistoryCollection();
        $history = new History();
        $history->setName('MyEntity')
                ->addDataObject($dto);

        $HistoryCollection->append($history);

        $HistoryStub =  $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $HistoryStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue($HistoryCollection));

        $sUT = new HistoryQuestion($HistoryStub, $context);
        $this->assertEquals($dto, $sUT->ask());
    }

    public function testEmptyHistory()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
    	->disableOriginalConstructor()
    	->getMock();
        $context->expects($this->any())
        ->method('log')
        ->will($this->returnValue(''));

        $HistoryCollection = new HistoryCollection();

        $HistoryStub =  $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $HistoryStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue($HistoryCollection));

        $sUT = new HistoryQuestion($HistoryStub, $context);
        $this->setExpectedException('RuntimeException');
        $sUT->ask();
    }
}