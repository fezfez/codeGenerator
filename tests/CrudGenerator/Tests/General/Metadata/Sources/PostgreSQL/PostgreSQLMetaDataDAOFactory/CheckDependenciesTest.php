<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;
use CrudGenerator\Metadata\MetaDataSource;

/**
 * @requires extension pdo_pgsql
 */
class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertEquals(
            true,
            PostgreSQLMetaDataDAOFactory::checkDependencies(new MetaDataSource())
        );
    }
}
