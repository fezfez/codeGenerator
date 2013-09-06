<?php
namespace CrudGenerator\Tests\PDO\MetaData\Sources\PDO\PgSql\PDOMetaDataDAO;

use CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

class GetMetadataForTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = include __DIR__ . '/../config.php';

        $suT = PDOMetaDataDAOFactory::getInstance($pdoConfig);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO',
            $suT->getMetadataFor('messages')
        );
    }
}
