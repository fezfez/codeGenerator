<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAO;

use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testTypeccc()
    {
        $config = include __DIR__ . '/../Config.php';

        $suT = JsonMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('data')
        );
    }
}
