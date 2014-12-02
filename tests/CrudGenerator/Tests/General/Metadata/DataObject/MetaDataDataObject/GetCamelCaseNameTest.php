<?php
namespace CrudGenerator\Tests\General\Metadata\DataObject\MetaDataDataObject;

use CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;

class GetCamelCaseNameTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetadataDataObjectPostgreSQL(
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
