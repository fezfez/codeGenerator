<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Web\MetaDataQuestion;

use CrudGenerator\Generators\Questions\Web\MetaDataQuestion;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;
use CrudGenerator\Context\WebContext;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
               ->setMetaDataDAO('Name')
               ->setMetaDataDAOFactory('test');

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigDAO')
            ->disableOriginalConstructor()
            ->getMock();

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO')
            ->disableOriginalConstructor()
            ->getMock();
        $doctrine2MetaDataDAOStub
            ->expects($this->exactly(2))
            ->method('getAllMetadata')
            ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $metaDataSourceFactoryStub
             ->expects($this->exactly(2))
             ->method('create')
             ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo(null))
             ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($source);
    }

    public function testWithConfig()
    {
        $config = new PostgreSQLConfig();
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetaDataDAO('Name')
        ->setConfig($config);

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigDAO')
        ->disableOriginalConstructor()
        ->getMock();

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO')
        ->disableOriginalConstructor()
        ->getMock();
        $doctrine2MetaDataDAOStub
        ->expects($this->exactly(2))
        ->method('getAllMetadata')
        ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->exactly(2))
        ->method('create')
        ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo($config))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($source);
    }

    public function testOkWithPreselected()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetaDataDAO('Name');

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigDAO')
        ->disableOriginalConstructor()
        ->getMock();

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO')
        ->disableOriginalConstructor()
        ->getMock();
        $doctrine2MetaDataDAOStub
        ->expects($this->exactly(2))
        ->method('getAllMetadata')
        ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->exactly(2))
        ->method('create')
        ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context = $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue('MyName'));

        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);
        $this->assertEquals($metaData, $sUT->ask($source));
    }

    public function testFailWithPreselected()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetaDataDAO('Name');

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigDAO')
        ->disableOriginalConstructor()
        ->getMock();

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO')
        ->disableOriginalConstructor()
        ->getMock();
        $doctrine2MetaDataDAOStub
        ->expects($this->exactly(2))
        ->method('getAllMetadata')
        ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->exactly(2))
        ->method('create')
        ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context = $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue('fakename'));

        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($source);
    }
}
