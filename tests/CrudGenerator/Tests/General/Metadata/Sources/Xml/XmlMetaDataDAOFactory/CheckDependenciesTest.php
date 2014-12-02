<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml\XmlMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAOFactory;
use CrudGenerator\Metadata\MetaDataSource;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertTrue(XmlMetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
