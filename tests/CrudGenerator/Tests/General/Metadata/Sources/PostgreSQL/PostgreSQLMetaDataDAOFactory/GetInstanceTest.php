<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

/**
 * @requires extension pdo_pgsql
 */
class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $pdoConfig = include __DIR__ . '/../config.php';

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAO',
            PostgreSQLMetaDataDAOFactory::getInstance($pdoConfig)
        );
    }
}
