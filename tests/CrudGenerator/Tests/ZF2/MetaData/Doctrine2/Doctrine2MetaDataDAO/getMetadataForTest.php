<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class getMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = Doctrine2MetaDataDAOFactory::getInstance();

        $metadata = $suT->getMetadataFor('TestZf2\Entities\NewsEntity');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Doctrine2\MetadataDataObjectDoctrine2',
            $metadata
        );

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection',
            $metadata->getColumnCollection()
        );
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection',
            $metadata->getRelationCollection()
        );

        $identifier = $metadata->getIdentifier();
        $this->assertEquals(
            'id',
            $identifier[0]
        );

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

