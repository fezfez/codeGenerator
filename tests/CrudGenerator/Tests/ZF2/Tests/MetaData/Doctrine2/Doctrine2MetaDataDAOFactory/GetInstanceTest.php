<?php
namespace CrudGenerator\Tests\ZF2\Tests\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testOkTOTO()
    {
         chdir(__DIR__ . '/../../../');

         $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\MetaDataDAOCache',
             Doctrine2MetaDataDAOFactory::getInstance()
        );
    }
}
