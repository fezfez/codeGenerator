<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFinder;

use CrudGenerator\MetaData\MetaDataSourceFinder;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\Tests\TestCase;

class GetAllAdaptersTest extends TestCase
{
    public function testSearchMetadataSource()
    {
        $classAwake             = $this->createMock('CrudGenerator\Utils\ClassAwake');
        $metaDataSourceHydrator = $this->createMock('CrudGenerator\MetaData\MetaDataSourceHydrator');

        $classList = array('tutu' => 'Tutu', 'toto' => 'Toto');

        $classAwakeExpects = $classAwake->expects($this->once());
        $classAwakeExpects->method('wakeByInterfaces');
        $classAwakeExpects->willReturn($classList);

        $metaDataSourceHydratorExpects = $metaDataSourceHydrator->expects($this->at(0));
        $metaDataSourceHydratorExpects->method('adapterNameToMetaDataSource');
        $metaDataSourceHydratorExpects->with('Tutu');

        $metaDataSourceHydratorExpects = $metaDataSourceHydrator->expects($this->at(1));
        $metaDataSourceHydratorExpects->method('adapterNameToMetaDataSource');
        $metaDataSourceHydratorExpects->with('Toto');

        $suT = new MetaDataSourceFinder($classAwake, $metaDataSourceHydrator);

        $adapterCollection = $suT->getAllAdapters();

        $this->assertCount(2, $adapterCollection);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $adapterCollection);
    }
}
