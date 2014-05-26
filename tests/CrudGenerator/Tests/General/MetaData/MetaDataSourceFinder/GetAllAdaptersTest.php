<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFinder;

use CrudGenerator\MetaData\MetaDataSourceFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;

class GetAllAdaptersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        chdir(__DIR__);
        $classAwake = new ClassAwake();

        $metaDataSourceHydrator = new \CrudGenerator\MetaData\MetaDataSourceHydrator(new FileManager());
        $suT = new MetaDataSourceFinder($classAwake, $metaDataSourceHydrator);

        $adapterCollection = $suT->getAllAdapters();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSourceCollection',
            $adapterCollection
        );
    }

    /*public function testFail()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(false));

        $classAwake  = new ClassAwake();

        $suT = new MetaDataSourceFinder($stubFileManager, $classAwake);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSourceCollection',
            $suT->getAllAdapters()
        );
    }*/
}
