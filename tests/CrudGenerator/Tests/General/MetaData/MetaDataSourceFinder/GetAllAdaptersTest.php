<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFinder;

use CrudGenerator\MetaData\MetaDataSourceFinder;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\Utils\ClassAwake;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        chdir(__DIR__);

        $classAwake             = new ClassAwake();
        $metaDataSourceHydrator = new MetaDataSourceHydrator();

        $suT = new MetaDataSourceFinder($classAwake, $metaDataSourceHydrator);

        $adapterCollection = $suT->getAllAdapters();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSourceCollection',
            $adapterCollection
        );
    }
}
