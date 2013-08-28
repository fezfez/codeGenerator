<?php
namespace CrudGenerator\Tests\PDO\Sources\MetaData\PDO\PgSql\PDOMetaDataDAO;

use CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = include __DIR__ . '/../config.php';

        $suT = PDOMetaDataDAOFactory::getInstance($pdoConfig);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection',
            $suT->getAllMetadata()
        );
    }
}
