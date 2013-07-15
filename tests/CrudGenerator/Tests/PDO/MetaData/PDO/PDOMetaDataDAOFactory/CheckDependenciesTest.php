<?php
namespace CrudGenerator\Tests\PDO\MetaData\PDO\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;

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
