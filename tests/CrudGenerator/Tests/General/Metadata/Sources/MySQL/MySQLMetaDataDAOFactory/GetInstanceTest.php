<?php

namespace CrudGenerator\Tests\General\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $config = include __DIR__.'/../Config.php';

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAO',
            MySQLMetaDataDAOFactory::getInstance($config)
        );
    }
}
