<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\PgSql\PostgreSQLMetaDataDAO;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $PostgreSQLConfig = include __DIR__ . '/../config.php';

        $suT = PostgreSQLMetaDataDAOFactory::getInstance($PostgreSQLConfig);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
            $suT->getMetadataFor('messages')
        );
    }
}
