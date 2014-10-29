<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

/**
 * @requires extension pdo_pgsql
 */
class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            PostgreSQLMetaDataDAOFactory::getDescription()
        );
    }
}
