<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;
use CrudGenerator\MetaData\MetaDataSource;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertFalse(Doctrine2MetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
