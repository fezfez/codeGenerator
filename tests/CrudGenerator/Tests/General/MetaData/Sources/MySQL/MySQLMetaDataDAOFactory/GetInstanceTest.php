<?php

namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;

/**
 * @requires extension pdo_mysql
 */
class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $config = include __DIR__ . '/../Config.php';

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO',
            MySQLMetaDataDAOFactory::getInstance(new PdoDriver(), $config)
        );
    }
}
