<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertFalse(Doctrine2MetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
