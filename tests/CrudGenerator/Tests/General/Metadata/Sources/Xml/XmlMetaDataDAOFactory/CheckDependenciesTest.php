<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\Xml\XmlMetaDataDAOFactory;

use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAOFactory;

class CheckDependenciesTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertTrue(XmlMetaDataDAOFactory::checkDependencies(new MetaDataSource()));
    }
}
