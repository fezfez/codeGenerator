<?php
namespace CrudGenerator\Tests\ZF2\Tests\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

use CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        chdir(__DIR__.'/../../../');

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAO',
             Doctrine2MetaDataDAOFactory::getInstance()
        );
    }
}
