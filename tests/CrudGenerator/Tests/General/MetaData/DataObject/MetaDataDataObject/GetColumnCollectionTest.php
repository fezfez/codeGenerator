<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataDataObject;

use CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;

class GetColumnCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $column = new MetaDataColumn();
        $column->setName('id')
        ->setNullable(true)
        ->setType('integer')
        ->setLength('100');

        $sUT->appendColumn($column);

        $columnCollection = $sUT->getColumnCollection();
        $this->assertInstanceOf('CrudGenerator\MetaData\DataObject\MetaDataColumnCollection', $columnCollection);

        $columnCollectionWithoutIdentifier = $sUT->getColumnCollection();
        $this->assertInstanceOf('CrudGenerator\MetaData\DataObject\MetaDataColumnCollection', $columnCollectionWithoutIdentifier);

        foreach ($columnCollectionWithoutIdentifier as $column) {
        	$this->assertInstanceOf('CrudGenerator\MetaData\DataObject\MetaDataColumn', $column);
            $this->assertEquals(
                false,
                $column->isPrimaryKey()
            );
        }
    }
}
