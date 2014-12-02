<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAO;

use CrudGenerator\Metadata\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory;

/**
 * @requires extension pdo_pgsql
 */
class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzadzdaz()
    {
        $PostgreSQLConfig = include __DIR__.'/../config.php';

        $suT = PostgreSQLMetaDataDAOFactory::getInstance($PostgreSQLConfig);

        $allMetaData = $suT->getAllMetadata();
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\DataObject\MetaDataCollection',
            $allMetaData
        );

        foreach ($allMetaData as $metaData) {
            $this->assertInstanceOf(
                'CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL',
                $metaData
            );

            $primaryKeys      = $metaData->getIdentifier();
            $columnCollection = $metaData->getColumnCollection();

            foreach ($columnCollection as $column) {
                $this->assertInstanceOf(
                    'CrudGenerator\Metadata\DataObject\MetaDataColumn',
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
