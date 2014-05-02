<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\PgSql\PostgreSQLMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertEquals(
            true,
            PostgreSQLMetaDataDAOFactory::checkDependencies()
        );
    }
}
