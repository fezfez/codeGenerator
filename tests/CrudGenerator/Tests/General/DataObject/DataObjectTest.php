<?php
namespace CrudGenerator\Tests\General\DataObject\DataObject;

use CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\DataObject;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $metaData = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dataObject = new DataObject();
        $dataObject->setNamespace('my\namespace')
                   ->setMetadata($metaData)
                   ->addEnvironnementValue('framework', 'zend2');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
            $dataObject->getMetadata()
        );

        $this->assertEquals(
            'my\namespace',
            $dataObject->getNamespace()
        );

        $this->assertEquals(
            'zend2',
            $dataObject->getEnvironnement('framework')
        );

        $this->setExpectedException('InvalidArgumentException');
        $dataObject->getEnvironnement('exception');
    }
}
