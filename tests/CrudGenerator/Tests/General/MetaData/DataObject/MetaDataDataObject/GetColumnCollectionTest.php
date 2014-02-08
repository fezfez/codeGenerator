<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataDataObject;

use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;

class GetColumnCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPDO(
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

        $columnCollectionWithoutIdentifier = $sUT->getColumnCollection();

        foreach ($columnCollectionWithoutIdentifier as $column) {
            $this->assertEquals(
                false,
                $column->isPrimaryKey()
            );
        }
    }
}
