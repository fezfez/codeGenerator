<?php
namespace CrudGenerator\Tests\MetaData\PDO\PDOMetaDataDAO;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\PDO\PDOConfig;

class getMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = new PDOConfig();
        $pdoConfig->setDatabaseName('sqlite2::memory:')
                  ->setType('sqlite2')
                  ->setPassword(null)
                  ->setUser(null)
                  ->setPort(null)
                  ->setHost(null);

        $suT = PDOMetaDataDAOFactory::getInstance($pdoConfig);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\PDO\MetadataDataObjectPDO',
            $suT->getMetadataFor('test')
        );
    }
}

