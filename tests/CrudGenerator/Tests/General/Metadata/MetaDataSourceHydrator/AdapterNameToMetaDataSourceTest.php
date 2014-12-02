<?php
namespace CrudGenerator\Tests\General\Metadata\MetaDataSourceHydrator;

use CrudGenerator\Metadata\MetaDataSourceHydrator;
use CrudGenerator\Tests\TestCase;

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
