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
        $driver->response('configDatabaseName', 'code_generator');
        $driver->response('configHost', 'localhost');
        $driver->response('configUser', 'postgres');
        $driver->response('configPort', '5432');
        $driver->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::POSTGRESQL);

        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\MetaDataDAOCache',
            $sUT->create('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory', $driver)
        );
    }
}
