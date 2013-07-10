<?php
namespace CrudGenerator\Tests\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAO',
             Doctrine2MetaDataDAOFactory::getInstance()
        );
    }
}

