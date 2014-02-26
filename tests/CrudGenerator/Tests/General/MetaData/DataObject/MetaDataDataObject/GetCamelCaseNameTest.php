<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataDataObject;

use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GetCamelCaseNameTest extends \PHPUnit_Framework_TestCase
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
            $sUT->getName(true)
        );

        $this->assertEquals(
            'manyToMany',
            $sUT->getName(false)
        );

        $this->assertEquals(
            'many_to_many',
            $sUT->getOriginalName()
        );
    }
}
