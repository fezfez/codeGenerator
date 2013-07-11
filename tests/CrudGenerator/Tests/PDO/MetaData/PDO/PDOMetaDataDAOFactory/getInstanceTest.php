<?php
namespace CrudGenerator\Tests\PDO\MetaData\PDO\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\PDO\PDOConfig;

class getInstanceTest extends \PHPUnit_Framework_TestCase
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

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\PDO\PDOMetaDataDAO',
             PDOMetaDataDAOFactory::getInstance($pdoConfig)
        );
    }
}

