<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSource;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;

class MetaDataSourceDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterAndGetter()
    {
        $driverConfig = new DriverConfig("test");
        $source       = new MetaDataSource();

        $source->setConfig($driverConfig);
        $source->setDefinition('definition');
        $source->setFalseDependencie('false');
        $source->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory');
        $source->setMetadataDao("CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO");

        $this->assertInstanceOf('CrudGenerator\MetaData\Driver\DriverConfig', $source->getConfig());
        $this->assertEquals('definition', $source->getDefinition());
        $this->assertEquals('false', $source->getFalseDependencies());
        $this->assertEquals('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO', $source->getMetaDataDao());
        $this->assertEquals(
            'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory',
            $source->getMetadataDaoFactory()
        );
    }
}
