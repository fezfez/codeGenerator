<?php
namespace CrudGenerator\Tests\General\MetaData\DataObject\MetaDataRelationColumn;

use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;

class MetaDataRelationColumnDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetaDataRelationColumn();

        $sUT->setAssociationType('many_to_many')
            ->setFieldName('fields')
            ->setFullName('Full\Name');

        $this->assertEquals(
            'many_to_many',
            $sUT->getAssociationType()
        );

        $this->assertEquals(
            'fields',
            $sUT->getFieldName()
        );

        $this->assertEquals(
            'Full\Name',
            $sUT->getFullName()
        );

        $this->assertEquals(
            'Name',
            $sUT->getName()
        );

        $sUT->setFullName('Name');

        $this->assertEquals(
            'Name',
            $sUT->getName()
        );
    }
}
