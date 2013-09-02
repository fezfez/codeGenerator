<?php
namespace CrudGenerator\Tests\PDO\Sources\MetaData\PDO\PgSql\PDOMetaDataDAO;

use CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzadzdaz()
    {
        $pdoConfig = include __DIR__ . '/../config.php';

        $suT = PDOMetaDataDAOFactory::getInstance($pdoConfig);

        $allMetaData = $suT->getAllMetadata();
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataCollection',
            $allMetaData
        );

        foreach ($allMetaData as $metaData) {
            $this->assertInstanceOf(
                'CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO',
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
