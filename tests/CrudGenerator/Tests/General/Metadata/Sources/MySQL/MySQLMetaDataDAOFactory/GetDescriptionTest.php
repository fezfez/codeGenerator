<?php

namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\MetaDataSource',
            MySQLMetaDataDAOFactory::getDescription()
        );
    }
}
