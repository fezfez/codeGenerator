<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSource;

use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\MetaDataSource;

class MetaDataSourceDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterAndGetter()
    {
        $driverConfig = new DriverConfig("test");
        $source       = new MetaDataSource();

        $source->setConfig($driverConfig);
        $source->setDefinition('definition');
        $source->setFalseDependencie('false');
        $source->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory');
        $source->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO");

        $this->assertInstanceOf('CrudGenerator\Metadata\Driver\DriverConfig', $source->getConfig());
        $this->assertEquals('definition', $source->getDefinition());
        $this->assertEquals('false', $source->getFalseDependencies());
        $this->assertEquals('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO', $source->getMetaDataDao());
        $this->assertEquals(
            'CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory',
            $source->getMetadataDaoFactory()
        );
    }
}
