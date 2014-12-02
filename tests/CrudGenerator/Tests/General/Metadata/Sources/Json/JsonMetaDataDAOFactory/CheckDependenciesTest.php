<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\Json\JsonMetaDataDAOFactory;

use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertTrue(JsonMetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
