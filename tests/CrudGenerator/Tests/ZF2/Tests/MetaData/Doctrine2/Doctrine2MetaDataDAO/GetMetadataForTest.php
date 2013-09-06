<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\Utils\FileManager;

class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue(include __DIR__ . '/../../../../config/application.config.php'));

        $sm = ZendFramework2Environnement::getDependence($stubFileManager);
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $suT = new Doctrine2MetaDataDAO($em);

        $metadata = $suT->getMetadataFor('TestZf2\Entities\NewsEntity');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2',
            $metadata
        );

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataColumnCollection',
            $metadata->getColumnCollection()
        );
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataRelationCollection',
            $metadata->getRelationCollection()
        );

        $identifiers = $metadata->getIdentifier();
        foreach ($identifiers as $identifier) {
            $this->assertInstanceOf(
                'CrudGenerator\MetaData\DataObject\MetaDataColumn',
                $identifier
            );
            $this->assertEquals(true, $identifier->isPrimaryKey());
        }

        $this->assertEquals(
            'TestZf2\Entities\NewsEntity',
            $metadata->getName()
        );

        $relationCollection = $metadata->getRelationCollection();

        $manyToOneRelation = $relationCollection->offsetGet(0);
        $this->assertEquals(
            'manyToOne',
            $manyToOneRelation->getAssociationType()
        );

        $oneToOneRelation = $relationCollection->offsetGet(1);
        $this->assertEquals(
            'oneToOne',
            $oneToOneRelation->getAssociationType()
        );

        $oneToManyRelation = $relationCollection->offsetGet(2);
        $this->assertEquals(
            'oneToMany',
            $oneToManyRelation->getAssociationType()
        );

        $manyToManyRelation = $relationCollection->offsetGet(3);
        $this->assertEquals(
            'manyToMany',
            $manyToManyRelation->getAssociationType()
        );

    }
}
