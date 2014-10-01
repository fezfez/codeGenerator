<?php
namespace CrudGenerator\Tests\General\Adapater\MetaDataSource;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;

class MetaDataSourceDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $driverConfig = new DriverConfig("test");

        $adapater = new MetaDataSource();

        $adapater->setConfig($driverConfig)
                 ->setDefinition('definition')
                 ->setFalseDependencie('false')
                 ->setMetadataDao('name')
                 ->setMetadataDaoFactory('test');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Driver\DriverConfig',
            $adapater->getConfig()
        );

        $this->assertEquals(
            'definition',
            $adapater->getDefinition()
        );

        $this->assertEquals(
            'false',
            $adapater->getFalseDependencies()
        );

        $this->assertEquals(
            'name',
            $adapater->getMetaDataDao()
        );

        $this->assertEquals(
            'test',
            $adapater->getMetadataDaoFactory()
        );
    }
}
