<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\History\HistoryManager;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\Tests\TestCase;

class CreateTest extends TestCase
{
    public function testInstance()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager     = $this->createMock('\CrudGenerator\Utils\FileManager');
        $stubHistoryHydrator = $this->createMock('\CrudGenerator\History\HistoryHydrator');

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

        $dataObject = new DataObject();
        $dataObject->setMetadata($metadata);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDto($dataObject)
                        ->setMetadataSource(new MetaDataSource());

        $sUT->create($generatorDTO);
    }

    public function testFail()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager     = $this->createMock('\CrudGenerator\Utils\FileManager');
        $stubHistoryHydrator = $this->createMock('\CrudGenerator\History\HistoryHydrator');

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDto(new DataObject());

        $this->setExpectedException('InvalidArgumentException');
        $sUT->create($generatorDTO);
    }
}
