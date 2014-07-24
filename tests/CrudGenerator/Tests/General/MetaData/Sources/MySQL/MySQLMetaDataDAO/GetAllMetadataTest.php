<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAO;

use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $config = include __DIR__ . '/../Config.php';

        $suT = MySQLMetaDataDAOFactory::getInstance($config);

        $allMetaData = $suT->getAllMetadata();
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataCollection',
            $allMetaData
        );

        foreach ($allMetaData as $metaData) {
            $this->assertInstanceOf(
                'CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL',
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
