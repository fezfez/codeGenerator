<?php
namespace CrudGenerator\Tests\General\History\HistoryHydrator;

use CrudGenerator\History\HistoryHydrator;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use KeepUpdate\ArrayValidatorFactory;
use CrudGenerator\History\History;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Generators\ResponseExpectedException;

class JsonToDtoTest extends TestCase
{
    public function testIsNotJson()
    {
        $rawMock = $this->createSut('CrudGenerator\History\HistoryHydrator');

        $this->setExpectedException('CrudGenerator\History\InvalidHistoryException');

        $sUT = $rawMock['instance']($rawMock['mocks']);

        $sUT->jsonToDto(null);
    }

    public function testTestWithInvalidSource()
    {
        $rawMock = $this->createSut('CrudGenerator\History\HistoryHydrator');

        $sourceExpects = $rawMock['mocks']['metadataSourceConfiguredQuestion']->expects($this->once());
        $sourceExpects->method('ask');
        $sourceExpects->will($this->throwException(new ResponseExpectedException()));

        $sUT = $rawMock['instance']($rawMock['mocks']);

        $this->setExpectedException('CrudGenerator\History\InvalidHistoryException');

        $sUT->jsonToDto($this->getValidJson('Doctrine2', 'Corp\NewsEntity'));
    }

    public function testTestWithInvalidMetadata()
    {
        $rawMock = $this->createSut('CrudGenerator\History\HistoryHydrator');

        $metaDataSourceName      = 'Doctrine2';
        $metaDataSource          = 'CrudGenerator\Metadata\MetaDataSource';
        $metaDataName            = 'Corp\NewsEntity';
        $stubMetadataSourceClass = $this->createMock($metaDataSource);

        $sourceExpects = $rawMock['mocks']['metadataSourceConfiguredQuestion']->expects($this->once());
        $sourceExpects->method('ask');
        $sourceExpects->with($this->equalTo($metaDataSourceName));
        $sourceExpects->will($this->returnValue($stubMetadataSourceClass));

        $metadataQuestionExpects = $rawMock['mocks']['metadataQuestion']->expects($this->once());
        $metadataQuestionExpects->method('ask');
        $metadataQuestionExpects->with($this->equalTo($stubMetadataSourceClass), $this->equalTo($metaDataName));
        $metadataQuestionExpects->will($this->throwException(new ResponseExpectedException()));

        $sUT = $rawMock['instance']($rawMock['mocks']);

        $this->setExpectedException('CrudGenerator\History\InvalidHistoryException');

        $sUT->jsonToDto($this->getValidJson($metaDataSourceName, $metaDataName));
    }

    public function testOk()
    {
        $stubMetadataSource         = $this->createMock('CrudGenerator\Generators\Questions\Metadata\MetadataQuestion');
        $stubMetadataSourceQuestion = $this->_(
            'CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion'
        );

        $sUT = new HistoryHydrator(
            $stubMetadataSourceQuestion,
            $stubMetadataSource,
            ArrayValidatorFactory::getInstance()
        );

        $metaDataSourceName      = 'Doctrine2';
        $metaDataSource          = 'CrudGenerator\Metadata\MetaDataSource';
        $metaDataName            = 'Corp\NewsEntity';
        $stubMetadataSourceClass = $this->createMock($metaDataSource);

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $metaData->setName('MyName');

        $stubMetadataSourceQuestion->expects($this->once())
        ->method('ask')
        ->with($this->equalTo($metaDataSourceName))
        ->will($this->returnValue($stubMetadataSourceClass));

        $stubMetadataSource->expects($this->once())
        ->method('ask')
        ->with($this->equalTo($stubMetadataSourceClass), $this->equalTo($metaDataName))
        ->will($this->returnValue($metaData));

        $this->assertInstanceOf(
            'CrudGenerator\History\History',
            $sUT->jsonToDto($this->getValidJson($metaDataSourceName, $metaDataName))
        );
    }

    /**
     * @param  string $metaDataSourceName
     * @param  string $metaDataName
     * @return string
     */
    private function getValidJson($metaDataSourceName, $metaDataName)
    {
        return json_encode(array(
            GeneratorDataObject::TEMPLATE_VARIABLE => array(),
            GeneratorDataObject::FILES             => array(),
            GeneratorDataObject::DIRECTORIES       => array(),
            GeneratorDataObject::ENVIRONNEMENT     => array(),
            GeneratorDataObject::DEPENDENCIES      => array(),
            GeneratorDataObject::NAME              => "toto",
            GeneratorDataObject::DTO => array(
                DataObject::METADATA => array(
                    "id"      => "CategorieEntity",
                    "dtCreat" => "CategorieEntity",
                    "nomLog"  => "CategorieEntity",
                    "name"    => $metaDataName,
                ),
                DataObject::STORE => array(),
                "formDirectory"         => null,
                "namespace"             => null,
                "modelName"             => null,
                "attributesDisplayName" => array(
                    "id"      => "id",
                    "dtCreat" => "Date de ",
                    "nomLog"  => "Nom log",
                ),
            ),
            GeneratorDataObject::METADATA_SOURCE => array(
                MetaDataSource::DEFINITION           => "Doctrine2",
                MetaDataSource::METADATA_DAO         => "CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAO",
                MetaDataSource::METADATA_DAO_FACTORY => "CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory",
                MetaDataSource::FALSE_DEPENDENCIES   => null,
                MetaDataSource::UNIQUE_NAME          => $metaDataSourceName,
                MetaDataSource::CONFIG               => null,
            ),
        ));
    }
}
