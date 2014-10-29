<?php
namespace CrudGenerator\Tests\ZF2\Tests\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;
use CrudGenerator\MetaData\MetaDataSource;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertEquals(
            false,
            Doctrine2MetaDataDAOFactory::checkDependencies(new MetaDataSource())
        );
    }
}
