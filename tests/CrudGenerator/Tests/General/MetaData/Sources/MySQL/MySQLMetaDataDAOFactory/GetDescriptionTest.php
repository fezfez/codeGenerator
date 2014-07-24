<?php

namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;
use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            MySQLMetaDataDAOFactory::getDescription()
        );
    }
}