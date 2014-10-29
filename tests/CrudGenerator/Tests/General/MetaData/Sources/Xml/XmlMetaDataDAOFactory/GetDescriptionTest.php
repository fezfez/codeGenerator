<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml\XmlMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAOFactory;

class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            XmlMetaDataDAOFactory::getDescription()
        );
    }
}
