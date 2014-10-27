<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceHydrator;

use CrudGenerator\Tests\TestCase;
use CrudGenerator\MetaData\MetaDataSourceHydrator;

class AdapterNameToMetaDataSourceTest extends TestCase
{
    public function testWithExistingClass()
    {
        $sUT = new MetaDataSourceHydrator();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            $sUT->adapterNameToMetaDataSource('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory')
        );
    }
}