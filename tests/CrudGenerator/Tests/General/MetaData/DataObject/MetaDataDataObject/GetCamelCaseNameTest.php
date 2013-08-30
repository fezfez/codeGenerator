<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataRelationColumn;

use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GetCamelNameTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPDO(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
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
