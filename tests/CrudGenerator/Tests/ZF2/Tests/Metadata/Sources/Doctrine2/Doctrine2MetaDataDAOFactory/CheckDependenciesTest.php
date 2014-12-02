<?php
namespace CrudGenerator\Tests\ZF2\Tests\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;
use CrudGenerator\Metadata\MetaDataSource;

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
