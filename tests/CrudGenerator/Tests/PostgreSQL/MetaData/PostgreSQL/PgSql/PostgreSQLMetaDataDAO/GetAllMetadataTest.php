<?php
namespace CrudGenerator\Tests\PostgreSQL\Sources\MetaData\PostgreSQL\PgSql\PostgreSQLMetaDataDAO;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

/**
 * @requires extension pdo_pgsql
 */
class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzadzdaz()
    {
        $PostgreSQLConfig = include __DIR__ . '/../config.php';

        $suT = PostgreSQLMetaDataDAOFactory::getInstance($PostgreSQLConfig);

        $allMetaData = $suT->getAllMetadata();
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataCollection',
            $allMetaData
        );

        foreach ($allMetaData as $metaData) {
            $this->assertInstanceOf(
                'CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
                $metaData
            );

            $primaryKeys      = $metaData->getIdentifier();
            $columnCollection = $metaData->getColumnCollection();

            foreach ($columnCollection as $column) {
                $this->assertInstanceOf(
                    'CrudGenerator\MetaData\DataObject\MetaDataColumn',
                    $column
                );
            }

            foreach ($primaryKeys as $primaryKey) {
                $this->assertContains($primaryKey, $columnCollection);
            }

            $columnCollectionWithoutPk = $metaData->getColumnCollection(true);
            foreach ($primaryKeys as $primaryKey) {
                $this->assertNotContains($primaryKey, $columnCollectionWithoutPk);
            }
        }
    }
}
