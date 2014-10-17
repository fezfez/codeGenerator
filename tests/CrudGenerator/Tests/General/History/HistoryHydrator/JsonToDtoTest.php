<?php
namespace CrudGenerator\Tests\General\History\HistoryHydrator;

use CrudGenerator\History\HistoryHydrator;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;

class JsonToDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        // @TODO improve unit test
        $stubMetadataSourceQuestion = $this->getMockBuilder(
            'CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Metadata\MetadataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubMetadataSourceQuestion, $stubMetadataSource);

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
                "name" : "toto",
                "dto": {
                    "metadata": {
                        "id": "CategorieEntity",
                        "dtCreat": "CategorieEntity",
                        "nomLog": "CategorieEntity",
                        "name" : "Corp\\\NewsEntity"
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
                "metaDataSource": {
                    "definition": "Doctrine2",
                    "metaDataDAO": "CrudGenerator\\\\MetaData\\\\Sources\\\\Doctrine2\\\\Doctrine2MetaDataDAO",
                    "metaDataDAOFactory": "CrudGenerator\\\\MetaData\\\\Sources\\\\Doctrine2\\\\Doctrine2MetaDataDAOFactory",
                    "falseDependencies": null,
                    "uniqueName" : "Doctrine2"
                },
                "dtoClass": "CrudGenerator\\\\GeneratorsEmbed\\\\CrudGenerator\\\\Crud",
                "Generators":{
                    "CrudGenerator\\\\GeneratorsEmbed\\\\CrudGenerator\\\\Crud":{
                        "options":{
                            "AttributeName":{
                                "id":"Id",
                                "dtCreat":"Date de cr\\u00e9ation",
                                "nomLog":"Nom log"
                            },
                            "WriteAction":"NewsEntity",
                            "PrefixRouteName":"news",
                            "DisplayName":"news",
                            "DisplayNames":"news",
                            "ModelNamespace":"Corp",
                            "ControllerName":"News",
                            "ControllerNamespace":"Application"
                        }
                    }
                }
            }';

        $this->assertInstanceOf('CrudGenerator\History\History', $sUT->jsonToDto($json));
    }
}
