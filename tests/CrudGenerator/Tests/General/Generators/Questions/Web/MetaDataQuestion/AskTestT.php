<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Cli\MetaDataQuestion;


use CrudGenerator\Generators\Questions\Cli\MetaDataQuestion;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;


class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
            ->disableOriginalConstructor()
            ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
            ->disableOriginalConstructor()
            ->getMock();

        $dialog
            ->expects($this->once())
            ->method('select')
            ->will($this->returnValue(0));


        $source = new MetaDataSource();
        $source->setDefinition('My definition')
               ->setName('Name');

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigReader')
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
        $doctrine2MetaDataDAOStub
            ->expects($this->once())
            ->method('getMetadataFor')
            ->will($this->returnValue($metaData));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $metaDataSourceFactoryStub
             ->expects($this->once())
             ->method('create')
             ->with($this->equalTo($source->getName() . 'Factory'),$this->equalTo(null))
             ->will($this->returnValue($doctrine2MetaDataDAOStub));


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($metaData, $sUT->ask($source));
    }

    public function testWithConfig()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $dialog
        ->expects($this->once())
        ->method('select')
        ->will($this->returnValue(0));


        $config = new PDOConfig();
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setName('Name')
        ->setConfig($config);

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigReader')
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
        $doctrine2MetaDataDAOStub
        ->expects($this->once())
        ->method('getMetadataFor')
        ->will($this->returnValue($metaData));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->once())
        ->method('create')
        ->with($this->equalTo($source->getName() . 'Factory'),$this->equalTo($config))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($metaData, $sUT->ask($source));
    }

    public function testOkWithPreselected()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $dialog
        ->expects($this->never())
        ->method('select');


        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setName('Name');

        $metaDataConfigReaderStub = $this->getMockBuilder('CrudGenerator\MetaData\Config\MetaDataConfigReader')
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
        $doctrine2MetaDataDAOStub
        ->expects($this->once())
        ->method('getMetadataFor')
        ->will($this->returnValue($metaData));

        $metaDataSourceFactoryStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $metaDataSourceFactoryStub
        ->expects($this->once())
        ->method('create')
        ->with($this->equalTo($source->getName() . 'Factory'),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));


        $sUT = new MetaDataQuestion($metaDataConfigReaderStub, $metaDataSourceFactoryStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($metaData, $sUT->ask($source, 'MyName'));
    }
}