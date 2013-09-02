<?php
namespace CrudGenerator\Tests\ZF2\MetaData\MetaDataSourceFactory;

use CrudGenerator\MetaData\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new MetaDataSourceFactory();

        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO',
            $sUT->create('\CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory')
        );
    }
}
