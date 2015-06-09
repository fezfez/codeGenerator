<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\Metadata\DataObject\MetaDataRelationColumn;
use CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAO;

class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testRetieveData()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue(include __DIR__ . '/../../../../../config/application.config.php'));

        $sm = ZendFramework2Environnement::getDependence($stubFileManager);
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $suT = new Doctrine2MetaDataDAO($em);

        $metadata = $suT->getMetadataFor('TestZf2\Entities\NewsEntity');

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Doctrine2\MetadataDataObjectDoctrine2',
            $metadata
        );

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\DataObject\MetaDataColumnCollection',
            $metadata->getColumnCollection()
        );
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\DataObject\MetaDataRelationCollection',
            $metadata->getRelationCollection()
        );

        $identifiers = $metadata->getIdentifier();
        foreach ($identifiers as $identifier) {
            $this->assertInstanceOf(
                'CrudGenerator\Metadata\DataObject\MetaDataColumn',
                $identifier
            );
            $this->assertEquals(true, $identifier->isPrimaryKey());
        }

        $this->assertEquals(
            'NewsEntity',
            $metadata->getName()
        );

        $this->assertEquals(
            'TestZf2\Entities\NewsEntity',
            $metadata->getOriginalName()
        );

        $relationCollection = $metadata->getRelationCollection();

        $manyToOneRelation = $relationCollection->offsetGet(0);

        $this->assertInstanceOf('CrudGenerator\Metadata\DataObject\MetaDataRelationColumn', $manyToOneRelation);

        $this->assertEquals(
            MetaDataRelationColumn::MANY_TO_ONE,
            $manyToOneRelation->getAssociationType()
        );

        $oneToOneRelation = $relationCollection->offsetGet(1);

        $this->assertInstanceOf('CrudGenerator\Metadata\DataObject\MetaDataRelationColumn', $oneToOneRelation);

        $this->assertEquals(
            MetaDataRelationColumn::ONE_TO_ONE,
            $oneToOneRelation->getAssociationType()
        );

        $oneToManyRelation = $relationCollection->offsetGet(2);

        $this->assertInstanceOf('CrudGenerator\Metadata\DataObject\MetaDataRelationColumn', $oneToManyRelation);

        $this->assertEquals(
            MetaDataRelationColumn::ONE_TO_MANY,
            $oneToManyRelation->getAssociationType()
        );

        $manyToManyRelation = $relationCollection->offsetGet(3);

        $this->assertInstanceOf('CrudGenerator\Metadata\DataObject\MetaDataRelationColumn', $manyToManyRelation);

        $this->assertEquals(
            MetaDataRelationColumn::MANY_TO_MANY,
            $manyToManyRelation->getAssociationType()
        );
    }
}
