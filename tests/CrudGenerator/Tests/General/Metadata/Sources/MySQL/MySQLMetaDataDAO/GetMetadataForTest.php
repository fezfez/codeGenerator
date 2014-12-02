<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\MySQL\MySQLMetaDataDAO;

use CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory;

/**
 * @requires extension pdo_mysql
 */
class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testTypecc()
    {
        $config = include __DIR__.'/../Config.php';

        $suT = MySQLMetaDataDAOFactory::getInstance($config);

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\MySQL\MetadataDataObjectMySQL',
            $suT->getMetadataFor('messages')
        );
    }
}
