<?php
namespace CrudGenerator\Tests\ZF2\Tests\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            Doctrine2MetaDataDAOFactory::getDescription()
        );
    }
}
