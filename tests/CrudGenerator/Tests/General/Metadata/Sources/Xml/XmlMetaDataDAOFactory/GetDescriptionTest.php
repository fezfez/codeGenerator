<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\Xml\XmlMetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAOFactory;

class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\MetaDataSource',
            XmlMetaDataDAOFactory::getDescription()
        );
    }
}
