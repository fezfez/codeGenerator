<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml\XmlMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAOFactory;
use CrudGenerator\MetaData\MetaDataSource;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertTrue(XmlMetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
