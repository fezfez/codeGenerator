<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinder;

use CrudGenerator\Adapter\AdapterFinder;
use CrudGenerator\FileManager;

class getAllAdapatersTest extends \PHPUnit_Framework_TestCase
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

        $doctrine2 = $adapterCollection->offsetGet(0);
        $this->assertInternalType(
            'string',
            $doctrine2->getName()
        );
        $this->assertEquals(
            null,
            $doctrine2->getFalseDependencies()
        );
        $this->assertInternalType(
            'string',
            $doctrine2->getDefinition()
        );
        $this->assertEquals(
            null,
            $doctrine2->getConfig()
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

