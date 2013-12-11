<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataDataObject;

use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GetColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPDO(
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
