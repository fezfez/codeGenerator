<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\PgSql\PostgreSQLMetaDataDAO;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory;

/**
 * @requires extension pdo_pgsql
 */
class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $PostgreSQLConfig = include __DIR__ . '/../config.php';

        $suT = PostgreSQLMetaDataDAOFactory::getInstance(PdoDriverFactory::getInstance(), $PostgreSQLConfig);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
            $suT->getMetadataFor('messages')
        );
    }
}
