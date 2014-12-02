<?php
namespace CrudGenerator\Tests\General\Metadata\DataObject\MetaDataDataObject;

use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;

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
        $this->assertInstanceOf('CrudGenerator\Metadata\DataObject\MetaDataColumnCollection', $columnCollection);

        $columnCollectionWithoutIdentifier = $sUT->getColumnCollection();
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\DataObject\MetaDataColumnCollection',
            $columnCollectionWithoutIdentifier
        );

        foreach ($columnCollectionWithoutIdentifier as $column) {
            $this->assertInstanceOf('CrudGenerator\Metadata\DataObject\MetaDataColumn', $column);
            $this->assertEquals(
                false,
                $column->isPrimaryKey()
            );
        }
    }
}
