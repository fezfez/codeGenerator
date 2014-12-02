<?php
namespace CrudGenerator\Tests\General\Metadata\DataObject\MetaDataDataObject;

use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;

class GetColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $sUT->setName('many_to_many');

        $column = new MetaDataColumn();
        $column->setName('toto');

        $sUT->appendColumn($column);

        $this->assertEquals(
            $column,
            $sUT->getColumn('toto')
        );
    }
}
