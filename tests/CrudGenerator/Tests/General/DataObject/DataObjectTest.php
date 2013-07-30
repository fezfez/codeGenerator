<?php
namespace CrudGenerator\Tests\General\DataObject\DataObject;

use CrudGenerator\MetaData\PDO\MetadataDataObjectPDO;
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $metaData = new MetadataDataObjectPDO(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );

        $dataObject = new Architect();
        $dataObject->setDirectory('dir')
                   ->setEntity('my\entity')
                   ->setGenerator('generator')
                   ->setMetadata($metaData)
                   ->setModule('module')
                   ->setNamespace('my\namespace');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\PDO\MetadataDataObjectPDO',
            $dataObject->getMetadata()
        );

        $this->assertEquals(
            'dir',
            $dataObject->getDirectory()
        );

        $this->assertEquals(
            'my\entity',
            $dataObject->getEntity()
        );

        $this->assertEquals(
            'entity',
            $dataObject->getEntityName()
        );

        $this->assertEquals(
            'generator',
            $dataObject->getGenerator()
        );

        $this->assertEquals(
            'module',
            $dataObject->getModule()
        );

        $this->assertEquals(
            'my\namespace',
            $dataObject->getNamespace()
        );

        $this->assertEquals(
            'my/namespace',
            $dataObject->getNamespacePath()
        );

        $dataObject->setEntity('entity');

        $this->assertEquals(
            'entity',
            $dataObject->getEntityName()
        );
    }
}
