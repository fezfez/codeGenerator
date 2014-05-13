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
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
            ->disableOriginalConstructor()
            ->getMock();

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
            ->expects($this->once())
            ->method('getAllMetadata')
            ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $metaDataSourceFactoryStub
             ->expects($this->once())
             ->method('create')
             ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo(null))
             ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $app =  $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();
        $context = new WebContext($app);


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);

        $this->setExpectedException('InvalidArgumentException');

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
        $metaDataConfigReaderStub->expects($this->once())
        ->method('config')
        ->with($this->equalTo($config))
        ->will($this->returnValue($config));

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
        ->expects($this->once())
        ->method('getAllMetadata')
        ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->once())
        ->method('create')
        ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo($config))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $app =  $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();
        $context = new WebContext($app);


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);
        $this->assertEquals(array(array('id' => 'MyName', 'label' => 'MyName')), $sUT->ask($source));
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
        ->expects($this->once())
        ->method('getAllMetadata')
        ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->once())
        ->method('create')
        ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $app =  $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();
        $context = new WebContext($app);


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);
        $this->assertEquals($metaData, $sUT->ask($source, 'MyName'));
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
        ->expects($this->once())
        ->method('getAllMetadata')
        ->will($this->returnValue($metaDataCollection));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->once())
        ->method('create')
        ->with($this->equalTo($source->getMetaDataDAOFactory()),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $app =  $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();
        $context = new WebContext($app);


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $context);

        $this->setExpectedException('InvalidArgumentException');

        $sUT->ask($source, 'fail');
    }
}