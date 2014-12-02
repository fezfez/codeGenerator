<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAO;

use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $config = include __DIR__ . '/../ConfigWithNoColumnInFirstLevel.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $allMetaData = $suT->getAllMetadata();
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\DataObject\MetaDataCollection',
            $allMetaData
        );

        foreach ($allMetaData as $metaData) {
            $this->assertInstanceOf(
                'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
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
