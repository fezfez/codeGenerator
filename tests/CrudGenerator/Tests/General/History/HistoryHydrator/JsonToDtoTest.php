<?php
namespace CrudGenerator\Tests\General\History\HistoryHydrator;

use CrudGenerator\History\HistoryHydrator;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use KeepUpdate\ArrayValidatorFactory;
use CrudGenerator\History\History;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\Tests\TestCase;

class JsonToDtoTest extends TestCase
{
    public function testOk()
    {
        // @TODO improve unit test
        $stubMetadataSourceQuestion = $this->createMock(
            'CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion'
        );

        $stubMetadataSource = $this->createMock('CrudGenerator\Generators\Questions\Metadata\MetadataQuestion');

        $sUT = new HistoryHydrator(
            $stubMetadataSourceQuestion,
            $stubMetadataSource,
            ArrayValidatorFactory::getInstance()
        );

        $metaDataSourceName = 'Doctrine2';
        $metaDataSource     = 'CrudGenerator\MetaData\MetaDataSource';
        $metaDataName       = 'Corp\NewsEntity';

        $stubMetadataSourceClass = $this->getMockBuilder($metaDataSource)
        ->disableOriginalConstructor()
        ->getMock();

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

        $json = '{
                "' . GeneratorDataObject::NAME . '" : "toto",
                "' . GeneratorDataObject::DTO . '": {
                    "' . DataObject::METADATA . '": {
                        "id": "CategorieEntity",
                        "dtCreat": "CategorieEntity",
                        "nomLog": "CategorieEntity",
                        "name" : "Corp\\\NewsEntity"
                    },
                    "' . DataObject::STORE . '" : {

                    },
                    "formDirectory": null,
                    "namespace": null,
                    "modelName": null,
                    "attributesDisplayName": {
                        "id": "id",
                        "dtCreat": "Date de ",
                        "nomLog": "Nom log"
                    }
                },
                "' . GeneratorDataObject::METADATA_SOURCE . '": {
                    "definition": "Doctrine2",
                    "metaDataDAO": "CrudGenerator\\\\MetaData\\\\Sources\\\\Doctrine2\\\\Doctrine2MetaDataDAO",
                    "metaDataDAOFactory": "CrudGenerator\\\\MetaData\\\\Sources\\\\Doctrine2\\\\Doctrine2MetaDataDAOFactory",
                    "falseDependencies": null,
                    "uniqueName" : "Doctrine2",
                    "' . MetaDataSource::CONFIG . '" : null
                },
                "' . GeneratorDataObject::TEMPLATE_VARIABLE . '" : {

                },
                "' . GeneratorDataObject::FILES . '" : {

                },
                "' . GeneratorDataObject::DIRECTORIES . '" : {

                },
                "' . GeneratorDataObject::ENVIRONNEMENT . '" : {

                },
                "' . GeneratorDataObject::DEPENDENCIES . '" : {

                }
            }';

        $this->assertInstanceOf('CrudGenerator\History\History', $sUT->jsonToDto($json));
    }
}
