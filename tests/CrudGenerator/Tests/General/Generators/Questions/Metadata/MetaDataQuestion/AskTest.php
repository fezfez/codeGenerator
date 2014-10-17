<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Metadata\MetaDataQuestion;

use CrudGenerator\Generators\Questions\Metadata\MetaDataQuestion;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Driver\DriverConfig;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
               ->setMetadataDao('Name')
               ->setMetadataDaoFactory('test');

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder(
            'CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO'
            )
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
             ->with($this->equalTo($source->getMetadataDaoFactory()),$this->equalTo(null))
             ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetaDataQuestion($metaDataSourceFactoryStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($source);
    }

    /**
     * @param string $class
     */
    private function createMock($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }

    public function testWithConfig()
    {
        $config = new DriverConfig("test");
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetadataDao('Name')
        ->setConfig($config);

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder(
            'CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO'
        )
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
        ->with($this->equalTo($source->getMetadataDaoFactory()),$this->equalTo($config))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetaDataQuestion($metaDataSourceFactoryStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($source);
    }

    public function testOkWithPreselected()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetadataDao('Name');

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder(
            'CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO'
        )
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
        ->with($this->equalTo($source->getMetadataDaoFactory()),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue($metaData));

        $sUT = new MetaDataQuestion($metaDataSourceFactoryStub, $context);
        $this->assertEquals($metaData, $sUT->ask($source));
    }

    public function testFailWithPreselected()
    {
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetadataDao('Name');

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $metaDataCollection = new MetaDataCollection();
        $metaDataCollection->append($metaData);

        $doctrine2MetaDataDAOStub = $this->getMockBuilder(
            'CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO'
        )
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
        ->with($this->equalTo($source->getMetadataDaoFactory()),$this->equalTo(null))
        ->will($this->returnValue($doctrine2MetaDataDAOStub));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetaDataQuestion($metaDataSourceFactoryStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($source);
    }
}
