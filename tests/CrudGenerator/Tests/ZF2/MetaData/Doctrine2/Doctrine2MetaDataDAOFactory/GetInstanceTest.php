<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
         //$this->setExpectedException('CrudGenerator\EnvironnementResolver\EnvironnementResolverException');
         chdir(__DIR__);

         Doctrine2MetaDataDAOFactory::getInstance();
    }

    public function testFail()
    {
        $this->setExpectedException('CrudGenerator\EnvironnementResolver\EnvironnementResolverException');
        chdir(__DIR__ . '/../../../../');

        Doctrine2MetaDataDAOFactory::getInstance();
    }
}
