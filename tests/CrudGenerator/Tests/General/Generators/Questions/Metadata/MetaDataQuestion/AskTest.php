<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Metadata\MetaDataQuestion;

use CrudGenerator\Generators\Questions\Metadata\MetaDataQuestion;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    public function testOk()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition');
        $source->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory');
        $source->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO");

        $metaData = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $metaDataSourceFactoryStub = $this->createMock('CrudGenerator\Metadata\MetaDataSourceFactory');
        $doctrine2MetaDataDAOStub  = $this->createMock('CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAO');
        $context                   = $this->createMock('CrudGenerator\Context\CliContext');

        $doctrine2MetaDataDAOExpects = $doctrine2MetaDataDAOStub->expects($this->once());
        $doctrine2MetaDataDAOExpects->method('getAllMetadata');
        $doctrine2MetaDataDAOExpects->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryExpects = $metaDataSourceFactoryStub->expects($this->once());
        $metaDataSourceFactoryExpects->method('create');
        $metaDataSourceFactoryExpects->with($this->equalTo($source->getMetadataDaoFactory()), $this->equalTo(null));
        $metaDataSourceFactoryExpects->will($this->returnValue($doctrine2MetaDataDAOStub));

        $contextExpects = $context->expects($this->once());
        $contextExpects->method('askCollection');
        $contextExpects->will($this->returnValue($metaData));

        $sUT = new MetaDataQuestion($metaDataSourceFactoryStub, $context);

        $this->assertEquals($metaData, $sUT->ask($source));
    }
}
