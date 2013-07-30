<?php
namespace CrudGenerator\Tests\PDO\MetaData\PDO\PgSql\PDOConfig;

use CrudGenerator\MetaData\PDO\PDOConfig;

class PDOConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdo = new PDOConfig();

        $pdo->setDatabaseName('db')
            ->setHost('host')
            ->setPassword('password')
            ->setPort('port')
            ->setType('type')
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
            'type',
            $pdo->getType()
        );

        $this->assertEquals(
            'user',
            $pdo->getUser()
        );
    }
}
