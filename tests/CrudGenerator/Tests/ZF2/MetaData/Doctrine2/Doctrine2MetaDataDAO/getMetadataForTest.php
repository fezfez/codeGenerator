<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class getMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = Doctrine2MetaDataDAOFactory::getInstance();

        $metadata = $suT->getMetadataFor('Corp\News\NewsEntity');

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
            'Corp\News\NewsEntity',
            $metadata->getName()
        );
    }
}

