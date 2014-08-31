<?php

namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\MetaData\MetaDataSource;

use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;
use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $source = new MetaDataSource();

        $this->assertEquals(extension_loaded('pdo_mysql'), MySQLMetaDataDAOFactory::checkDependencies($source));
    }
}
