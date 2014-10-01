<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAO;

use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\Json\JsonConfig;
use CrudGenerator\MetaData\Driver\Web\WebDriver;
use CrudGenerator\Utils\FileManager;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $config = include __DIR__ . '/../Config.php';

        $suT = JsonMetaDataDAOFactory::getInstance(new WebDriver(new FileManager()), $config);

        $allMetaData = $suT->getAllMetadata();
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataCollection',
            $allMetaData
        );

        foreach ($allMetaData as $metaData) {
            $this->assertInstanceOf(
                'CrudGenerator\MetaData\Sources\Json\MetadataDataObjectJson',
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
