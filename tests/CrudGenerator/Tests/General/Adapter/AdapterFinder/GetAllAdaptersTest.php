<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinder;

use CrudGenerator\MetaData\MetaDataSourceFinder;
use CrudGenerator\Utils\FileManager;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = new FileManager();

        $suT = new MetaDataSourceFinder($fileManager);

        $adapterCollection = $suT->getAllAdapters();

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSourceCollection',
            $adapterCollection
        );
    }

    /**
     * @runTestsInSeparateProcesses
     */
    public function testFail()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(false));

        $suT = new MetaDataSourceFinder($stubFileManager);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSourceCollection',
            $suT->getAllAdapters()
        );
    }
}
