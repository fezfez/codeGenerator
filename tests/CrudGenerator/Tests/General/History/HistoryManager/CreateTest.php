<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubHistoryHydrator = $this->getMockBuilder('\CrudGenerator\History\HistoryHydrator')
        ->disableOriginalConstructor()
        ->getMock();

        $stubFileManager->expects($this->once())
                        ->method('isDir')
                        ->will($this->returnValue(false));
        $stubFileManager->expects($this->once())
                        ->method('mkdir')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->once())
                        ->method('isFile')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->once())
                        ->method('unlink')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->once())
                        ->method('filePutsContent')
                        ->will($this->returnValue(true));

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $metadata = new MetadataDataObjectDoctrine2(
        	new MetaDataColumnCollection(),
        	new MetaDataRelationCollection()
        );
        $metadata->setName('test');

        $dataObject = new Architect();
        $dataObject->setMetadata($metadata);

        $sUT->create($dataObject);
    }

    public function testFail()
    {
    	// wakeup classes
    	$generatorFinder = GeneratorFinderFactory::getInstance();
    	$generatorFinder->getAllClasses();

    	$stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
    	$stubHistoryHydrator = $this->getMockBuilder('\CrudGenerator\History\HistoryHydrator')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

    	$dataObject = new Architect();

    	$this->setExpectedException('InvalidArgumentException');
    	$sUT->create($dataObject);
    }
}
