<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetadataSource\MetadataSourceQuestion;

use CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion;
use CrudGenerator\Metadata\MetaDataSourceCollection;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    public function testOk()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source                   = new MetaDataSource();
        $sourceFailedDependencie  = new MetaDataSource();

        $source->setDefinition('My definition');
        $source->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory');
        $source->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO");

        $metadataSourceCollection->append($source);

        $sourceFailedDependencie->setDefinition('My definition');
        $sourceFailedDependencie->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory');
        $sourceFailedDependencie->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO");
        $sourceFailedDependencie->setFalseDependencie('My false dependencies');

        $metadataSourceCollection->append($sourceFailedDependencie);

        $sourceFinderStub = $this->createMock('CrudGenerator\Metadata\MetaDataSourceFinder');
        $context          = $this->createMock('CrudGenerator\Context\CliContext');

        $sourceFinderExpects = $sourceFinderStub->expects($this->once());
        $sourceFinderExpects->method('getAllAdapters');
        $sourceFinderExpects->will($this->returnValue($metadataSourceCollection));

        $contextExpects = $context->expects($this->once());
        $contextExpects->method('askCollection');
        $contextExpects->willReturn($source);

        $sUT = new MetadataSourceQuestion($sourceFinderStub, $context);

        $this->assertEquals($source, $sUT->ask());
    }
}
