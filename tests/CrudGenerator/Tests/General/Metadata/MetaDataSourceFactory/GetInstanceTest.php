<?php
namespace CrudGenerator\Tests\General\Metadata\MetaDataSourceFactory;

use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\Pdo\PdoDriver;
use CrudGenerator\Metadata\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $sUT    = new MetaDataSourceFactory();
        $driver = new DriverConfig('test');

        $driver->setDriver('CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory');
        $driver->response('configDatabaseName', 'code_generator');
        $driver->response('configHost', 'localhost');
        $driver->response('configUser', 'postgres');
        $driver->response('configPort', '5432');
        $driver->response('dsn', PdoDriver::POSTGRESQL);

        $this->assertInstanceOf(
            '\CrudGenerator\Metadata\Sources\MetaDataDAOCache',
            $sUT->create('\CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory', $driver)
        );
    }
}
