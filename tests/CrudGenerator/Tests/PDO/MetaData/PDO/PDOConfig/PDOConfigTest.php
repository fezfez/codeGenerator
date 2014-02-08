<?php
namespace CrudGenerator\Tests\PDO\MetaData\Sources\PDO\PgSql\PDOConfig;

use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

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

    public function testFail()
    {
        $pdo = new PDOConfig();

        $pdo->setDatabaseName('db')
        ->setHost('host')
        ->setPassword('password')
        ->setPort('port')
        ->setType('type')
        ->setUser('user');

        $stubConsole =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $this->setExpectedException('CrudGenerator\MetaData\Config\ConfigException');
        $pdo->test($stubConsole);
    }

    public function testOk()
    {
        $pdoConfig = include __DIR__ . '/../PgSql/config.php';

        $stubConsole =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $pdoConfig->test($stubConsole);
    }
}
