<?php

namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;
use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceFail()
    {
        $this->setExpectedException('InvalidArgumentException');

        MySQLMetaDataDAOFactory::getInstance();
    }

    public function testInstance()
    {
        $config = include __DIR__ . '/../Config.php';

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO',
            MySQLMetaDataDAOFactory::getInstance($config)
        );
    }
}