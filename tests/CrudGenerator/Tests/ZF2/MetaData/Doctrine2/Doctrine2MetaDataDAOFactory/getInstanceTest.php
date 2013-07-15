<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAOFactory;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
         $this->setExpectedException('CrudGenerator\EnvironnementResolver\EnvironnementResolverException');

         Doctrine2MetaDataDAOFactory::getInstance();
    }
}

