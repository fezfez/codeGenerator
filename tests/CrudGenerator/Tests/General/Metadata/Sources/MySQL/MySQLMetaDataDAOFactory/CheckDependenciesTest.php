<?php

namespace CrudGenerator\Tests\General\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory;

use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory;

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
