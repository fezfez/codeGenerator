<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

class PostgreSQLConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdo = new PostgreSQLConfig();

        $pdo->setDatabaseName('db')
            ->setHost('host')
            ->setPassword('password')
            ->setPort('port')
            ->setUser('user');

        $this->assertEquals(
            'db',
            $pdo->getDatabaseName()
        );

        $this->assertEquals(
            'host',
            $pdo->getHost()
        );

        $this->assertEquals(
            'password',
            $pdo->getPassword()
        );

        $this->assertEquals(
            'port',
            $pdo->getPort()
        );

        $this->assertEquals(
            'user',
            $pdo->getUser()
        );
    }

    public function testFail()
    {
        $pdo = new PostgreSQLConfig();

        $pdo->setDatabaseName('db')
        ->setHost('host')
        ->setPassword('password')
        ->setPort('port')
        ->setUser('user');


        $this->setExpectedException('CrudGenerator\MetaData\Config\ConfigException');
        $pdo->test();
    }

    public function testOk()
    {
        $pdoConfig = include __DIR__ . '/../PgSql/config.php';

        $pdoConfig->test();
    }
}
