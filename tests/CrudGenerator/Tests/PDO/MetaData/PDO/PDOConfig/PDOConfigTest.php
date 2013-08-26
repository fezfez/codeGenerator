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
        $pdo = new PDOConfig();

        $pdo->setDatabaseName('code_generator_test')
        ->setHost('localhost')
        ->setPort('5432')
        ->setType('pgsql')
        ->setUser('postgres');

        $stubConsole =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $pdo->test($stubConsole);
    }
}
