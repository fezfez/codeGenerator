<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\Xml\XmlMetaDataDAO;

use CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAOFactory;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $config = include __DIR__.'/../Config.php';

        $suT = XmlMetaDataDAOFactory::getInstance($config);

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
