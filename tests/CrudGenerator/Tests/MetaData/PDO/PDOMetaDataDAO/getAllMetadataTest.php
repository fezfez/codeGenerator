<?php
namespace CrudGenerator\Tests\MetaData\PDO\PDOMetaDataDAO;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\PDO\PDOConfig;

class getAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = new PDOConfig();
        $pdoConfig->setDatabaseName('sqlite2::memory:')
                  ->setType('sqlite2')
                  ->setPassword(null)
                  ->setUser(null);

        $suT = PDOMetaDataDAOFactory::getInstance($pdoConfig);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection',
            $suT->getAllMetadata()
        );
    }
}

