<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Metadata\MetaDataQuestion;

use CrudGenerator\Generators\Questions\Metadata\MetaDataQuestion;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    public function testOk()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition');
        $source->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory');
        $source->setMetadataDao("CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO");

        $metaData = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $metaDataSourceFactoryStub = $this->createMock('CrudGenerator\MetaData\MetaDataSourceFactory');
        $doctrine2MetaDataDAOStub  = $this->createMock('CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO');
        $context                   = $this->createMock('CrudGenerator\Context\CliContext');

        $doctrine2MetaDataDAOExpects = $doctrine2MetaDataDAOStub->expects($this->once());
        $doctrine2MetaDataDAOExpects->method('getAllMetadata');
        $doctrine2MetaDataDAOExpects->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryExpects = $metaDataSourceFactoryStub->expects($this->once());
        $metaDataSourceFactoryExpects->method('create');
        $metaDataSourceFactoryExpects->with($this->equalTo($source->getMetadataDaoFactory()),$this->equalTo(null));
        $metaDataSourceFactoryExpects->will($this->returnValue($doctrine2MetaDataDAOStub));

        $contextExpects = $context->expects($this->once());
        $contextExpects->method('askCollection');
        $contextExpects->will($this->returnValue($metaData));

        $sUT = new MetaDataQuestion($metaDataSourceFactoryStub, $context);

        $this->assertEquals($metaData, $sUT->ask($source));
    }
}
