<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAO;

use CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

/**
 * @requires extension pdo_pgsql
 */
class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $PostgreSQLConfig = include __DIR__ . '/../config.php';

        $suT = PostgreSQLMetaDataDAOFactory::getInstance($PostgreSQLConfig);

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
            $suT->getMetadataFor('messages')
        );
    }
}
