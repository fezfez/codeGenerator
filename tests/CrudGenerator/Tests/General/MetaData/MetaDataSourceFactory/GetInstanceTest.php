<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFactory;

use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $sUT = new MetaDataSourceFactory();

        $driver = new DriverConfig('test');
        $driver->setDriver('CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory');

        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\MetaDataDAOCache',
            $sUT->create('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory', $driver)
        );
    }
}
