<?php
namespace CrudGenerator\Tests\ZF2\MetaData\MetaDataSourceFactory;

use CrudGenerator\Metadata\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetaDataSourceFactory();

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\Sources\MetaDataDAOCache',
            $sUT->create('CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory')
        );
    }
}
