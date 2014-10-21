<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml\XmlMetaDataDAO;

use CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testRetireveData()
    {
        $config = include __DIR__ . '/../Config.php';

        $suT = XmlMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\Json\MetadataDataObjectJson',
            $suT->getMetadataFor('member')
        );
    }
}
