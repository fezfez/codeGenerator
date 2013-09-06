<?php
namespace CrudGenerator\Tests\PDO\MetaData\Sources\PDO\Sqlite\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = new PDOConfig();
        $pdoConfig->setDatabaseName('sqlite2::database:sqlite2')
                  ->setType('sqlite2')
                  ->setPassword(null)
                  ->setUser(null)
                  ->setPort(null)
                  ->setHost(null);

        $this->setExpectedException('InvalidArgumentException');

        PDOMetaDataDAOFactory::getInstance($pdoConfig);
    }
}
