<?php
namespace CrudGenerator\Tests\MetaData\PDO\PDOMetaDataDAOFactory;

use CrudGenerator\MetaData\PDO\PDOMetaDataDAOFactory;

class checkDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertEquals(
            true,
            PDOMetaDataDAOFactory::checkDependencies()
        );
    }
}
