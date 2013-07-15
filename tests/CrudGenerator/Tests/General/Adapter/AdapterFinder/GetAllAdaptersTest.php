<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinder;

use CrudGenerator\Adapter\AdapterFinder;
use CrudGenerator\FileManager;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = new FileManager();

        $suT = new AdapterFinder($fileManager);

        $adapterCollection = $suT->getAllAdapters();

        $this->assertInstanceOf(
            'CrudGenerator\Adapter\AdapterCollection',
            $adapterCollection
        );
    }

    /**
     * @runTestsInSeparateProcesses
     */
    public function testFail()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(false));

        $suT = new AdapterFinder($stubFileManager);

        $this->assertInstanceOf(
            'CrudGenerator\Adapter\AdapterCollection',
            $suT->getAllAdapters()
        );
    }
}
