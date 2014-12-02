<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceHydrator;

use CrudGenerator\Tests\TestCase;
use CrudGenerator\Metadata\MetaDataSourceHydrator;

class AdapterNameToMetaDataSourceTest extends TestCase
{
    public function testWithExistingClass()
    {
        $sUT = new MetaDataSourceHydrator();

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\MetaDataSource',
            $sUT->adapterNameToMetaDataSource('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory')
        );
    }
}