<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\Sqlite\PostgreSQLMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $PostgreSQLConfig = new PostgreSQLConfig();
        $PostgreSQLConfig->setDatabaseName('sqlite2::database:sqlite2')
                  ->setPassword(null)
                  ->setUser(null)
                  ->setPort(null)
                  ->setHost(null);

        $this->setExpectedException('InvalidArgumentException');

        PostgreSQLMetaDataDAOFactory::getInstance($PostgreSQLConfig);
    }
}
