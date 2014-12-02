<?php
namespace CrudGenerator\Tests\ZF2\Tests\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

class GetDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Metadata\MetaDataSource',
            Doctrine2MetaDataDAOFactory::getDescription()
        );
    }
}
