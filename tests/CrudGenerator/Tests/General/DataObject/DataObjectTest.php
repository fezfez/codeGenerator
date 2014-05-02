<?php
namespace CrudGenerator\Tests\General\DataObject\DataObject;

use CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class DataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $metaData = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dataObject = new Architect();
        $dataObject->setGenerator('generator')
                   ->setMetadata($metaData)
                   ->setNamespace('my\namespace')
                   ->addEnvironnementValue('framework', 'zend2');

        $this->assertEquals(
            'generator',
            $dataObject->getGenerator()
        );

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
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

        $this->setExpectedException('InvalidArgumentException');
        $dataObject->getEnvironnement('exception');
    }
}
