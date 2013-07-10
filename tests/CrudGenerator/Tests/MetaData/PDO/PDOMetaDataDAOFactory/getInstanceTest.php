<?php
namespace CrudGenerator\Tests\MetaData\PDO\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\PDO\PDOConfig;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = new PDOConfig();
        $pdoConfig->setDatabaseName('sqlite2::memory:')
                  ->setType('sqlite2')
                  ->setPassword(null)
                  ->setUser(null);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\PDO\PDOMetaDataDAO',
             PDOMetaDataDAOFactory::getInstance($pdoConfig)
        );
    }
}

