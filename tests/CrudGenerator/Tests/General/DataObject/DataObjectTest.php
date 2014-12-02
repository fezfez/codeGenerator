<?php
namespace CrudGenerator\Tests\General\DataObject\DataObject;

use CrudGenerator\DataObject;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $metaData = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dataObject = new DataObject();
        $dataObject->setNamespace('my\namespace')
                   ->setMetadata($metaData)
                   ->addEnvironnementValue('framework', 'zend2');

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
            $dataObject->getMetadata()
        );

        $this->assertEquals(
            'my\namespace',
            $dataObject->getNamespace()
        );

        $this->assertEquals(
            'zend2',
            $dataObject->getEnvironnement('framework')
        );

        $this->setExpectedException('CrudGenerator\EnvironnementNotDefinedException');
        $dataObject->getEnvironnement('exception');
    }

    public function testStorage()
    {
        $dataObject = new DataObject();

        $dataObject->setImATest('yyeaah');

        $this->assertEquals('yyeaah', $dataObject->getImATest());

        $dataObject->setImATest('yyeaah', 'toto');

        $this->assertEquals(array('yyeaah' => 'toto'), $dataObject->getImATest());
        $this->assertEquals('toto', $dataObject->getImATest('yyeaah'));

        $dataObject->setImATest('yyeaah');

        $this->assertEquals('yyeaah', $dataObject->getImATest());
    }

    public function testWrongUseOfStorageOnGet()
    {
        $dataObject = new DataObject();

        $this->setExpectedException('Exception');

        $dataObject->getImATest('test', 'fake');
    }

    public function testWrongUseOfStorageOnGetWithWrongAcess()
    {
        $dataObject = new DataObject();

        $this->setExpectedException('Exception');

        $dataObject->getImATest('test', 'fake', 'do', 'not');
    }

    public function testWrongUseOfStorageOnSet()
    {
        $dataObject = new DataObject();

        $this->setExpectedException('Exception');

        $dataObject->setTest('test', 'fake', 'fezfze');
    }

    public function testWrongUseOfCall()
    {
        $dataObject = new DataObject();

        $this->setExpectedException('Exception');

        $dataObject->seetTest('test', 'fake');
    }
}
