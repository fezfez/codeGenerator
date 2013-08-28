<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataRelationColumnDataObject;

use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

class GetCamelNameTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPDO(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );

        $sUT->setName('many_to_many');

        $this->assertEquals(
            'ManyToMany',
            $sUT->getCamelCaseName(true)
        );

        $this->assertEquals(
            'manyToMany',
            $sUT->getCamelCaseName(false)
        );
    }
}
