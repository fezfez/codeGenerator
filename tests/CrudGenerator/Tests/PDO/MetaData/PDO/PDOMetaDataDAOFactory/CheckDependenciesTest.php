<?php
namespace CrudGenerator\Tests\PDO\MetaData\Sources\PDO\PgSql\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertEquals(
            true,
            PDOMetaDataDAOFactory::checkDependencies()
        );
    }
}
