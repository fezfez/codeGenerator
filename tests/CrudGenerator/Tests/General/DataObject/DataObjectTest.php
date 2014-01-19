<?php
namespace CrudGenerator\Tests\General\DataObject\DataObject;

use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $metaData = new MetadataDataObjectPDO(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dataObject = new Architect();
        $dataObject->setDirectory('dir')
                   ->setEntity('my\entity')
                   ->setGenerator('generator')
                   ->setMetadata($metaData)
                   ->setModule('module')
                   ->setNamespace('my\namespace');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO',
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
            'module',
            $dataObject->getModuleName()
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
