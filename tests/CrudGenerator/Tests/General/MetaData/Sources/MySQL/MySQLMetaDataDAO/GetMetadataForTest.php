<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL\MySQLMetaDataDAO;

use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $config = include __DIR__ . '/../Config.php';

        $suT = MySQLMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL',
            $suT->getMetadataFor('messages')
        );
    }
}
