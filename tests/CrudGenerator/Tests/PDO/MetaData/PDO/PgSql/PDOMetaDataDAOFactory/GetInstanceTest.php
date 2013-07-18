<?php
namespace CrudGenerator\Tests\PDO\MetaData\PDO\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\PDO\PDOConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = include __DIR__ . '/../config.php';

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\PDO\PDOMetaDataDAO',
            PDOMetaDataDAOFactory::getInstance($pdoConfig)
        );
    }
}
