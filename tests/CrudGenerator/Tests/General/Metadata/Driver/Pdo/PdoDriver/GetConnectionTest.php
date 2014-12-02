<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\Pdo\PdoDriver;

use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\Pdo\PdoDriver;

class GetConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $sUT = new PdoDriver();

        $this->setExpectedException('CrudGenerator\Metadata\Config\ConfigException');

        $sUT->getConnection(new DriverConfig('im a name'));
    }

    public function testEmptyDsn()
    {
        $sUT    = new PdoDriver();
        $config = new DriverConfig('im a name');
        $config->response('configHost', 'tutu')
               ->response('configDatabaseName', 'toto')
               ->response('configUser', 'titi')
               ->response('configPassword', 'trtr');

        $this->setExpectedException('Exception');

        $sUT->getConnection($config);
    }

    public function testPgSQL()
    {
        $sUT    = new PdoDriver();
        $config = new DriverConfig('im a name');
        $config->response('configHost', 'tutu')
               ->response('configDatabaseName', 'toto')
               ->response('configUser', 'titi')
               ->response('configPassword', 'trtr')
               ->response('dsn', PdoDriver::POSTGRESQL);

        $this->setExpectedException('CrudGenerator\Metadata\Config\ConfigException');

        $sUT->getConnection($config);
    }

    public function testMySQL()
    {
        $sUT    = new PdoDriver();
        $config = new DriverConfig('im a name');
        $config->response('configHost', 'tutu')
        ->response('configDatabaseName', 'toto')
        ->response('configUser', 'titi')
        ->response('configPassword', 'trtr')
        ->response('dsn', PdoDriver::MYSQL);

        $this->setExpectedException('CrudGenerator\Metadata\Config\ConfigException');

        $sUT->getConnection($config);
    }

    public function testOracle()
    {
        $sUT    = new PdoDriver();
        $config = new DriverConfig('im a name');
        $config->response('configHost', 'tutu')
        ->response('configDatabaseName', 'toto')
        ->response('configUser', 'titi')
        ->response('configPassword', 'trtr')
        ->response('dsn', PdoDriver::ORACLE);

        $this->setExpectedException('CrudGenerator\Metadata\Config\ConfigException');

        $sUT->getConnection($config);
    }
}
