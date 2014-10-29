<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json\JsonMetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;

class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnCorrectType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            JsonMetaDataDAOFactory::getDescription()
        );
    }
}
