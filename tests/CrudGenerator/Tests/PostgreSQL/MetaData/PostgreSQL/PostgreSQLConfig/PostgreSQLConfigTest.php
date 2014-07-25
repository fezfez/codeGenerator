<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

/**
 * @requires extension pdo_pgsql
 */
class PostgreSQLConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdo = new PostgreSQLConfig();

        $pdo->setConfigDatabaseName('db')
            ->setConfigHost('host')
            ->setConfigPassword('password')
            ->setConfigPort('port')
            ->setConfigUser('user');

        $this->assertEquals(
            'db',
            $pdo->getConfigDatabaseName()
        );

        $this->assertEquals(
            'host',
            $pdo->getConfigHost()
        );

        $this->assertEquals(
            'password',
            $pdo->getConfigPassword()
        );

        $this->assertEquals(
            'port',
            $pdo->getConfigPort()
        );

        $this->assertEquals(
            'user',
            $pdo->getConfigUser()
        );
    }

    public function testFail()
    {
        $pdo = new PostgreSQLConfig();

        $pdo->setConfigDatabaseName('db')
        ->setConfigHost('host')
        ->setConfigPassword('password')
        ->setConfigPort('port')
        ->setConfigUser('user');

        $this->setExpectedException('CrudGenerator\MetaData\Config\ConfigException');
        $pdo->test();
    }

    public function testOk()
    {
        $pdoConfig = include __DIR__ . '/../PgSql/config.php';

        $pdoConfig->test();
    }
}
