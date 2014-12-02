<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAO;

use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;

class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testRetireveMainData()
    {
        $config = include __DIR__ . '/../ConfigWithNoColumnInFirstLevel.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('data')
        );

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('from')
        );
    }

    public function testFail()
    {
        $config = include __DIR__ . '/../ConfigWithNoColumnInFirstLevel.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $this->setExpectedException('Exception');

        $suT->getMetadataFor('unknowndata');
    }

    public function testWithFirstLevelDto()
    {
        $config = include __DIR__ . '/../ConfigFirstLevelDto.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('master')
        );
    }

    public function testWithColumnFirstLevelDto()
    {
        $config = include __DIR__ . '/../ConfigWithColumnInFirstLevel.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('data')
        );

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('from')
        );
    }

    public function testWithCollectionToBeMerge()
    {
        $config = include __DIR__ . '/../ConfigWithCollectionToBeMerge.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $metadata = $suT->getMetadataFor('data');

        $this->assertInstanceOf('CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson', $metadata);

        $this->assertCount(3, $metadata->getColumnCollection());
        $this->assertCount(0, $metadata->getRelationCollection());
    }

    public function testWithOnlyRelationOnFirstLevel()
    {
        $config = include __DIR__ . '/../ConfigWithOnlyRelationOnFirstLevel.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('data')
        );

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('tutu')
        );
    }
}
