<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\Metadata\MetaDataSource;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertTrue(JsonMetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
