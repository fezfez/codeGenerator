<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\File\Web\WebDriver;

use CrudGenerator\Metadata\Driver\File\Web\WebDriver;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Metadata\Driver\DriverConfig;

class GetFileTest extends TestCase
{
    public function testFailOnRetrieve()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');

        $url = 'toto';
        $fileManagerExpectsOnce = $fileManager->expects($this->once());
        $fileManagerExpectsOnce->method('fileGetContent');
        $fileManagerExpectsOnce->with($url);
        $fileManagerExpectsOnce->will($this->throwException(new \RuntimeException()));

        $sUT = new WebDriver($fileManager);
        $driverConfig = new DriverConfig('my name');
        $driverConfig->response('configUrl', $url);

        $this->setExpectedException('CrudGenerator\Metadata\Config\ConfigException');

        $sUT->getFile($driverConfig);
    }

    public function testOk()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');

        $url = 'toto';
        $result = 'tutut';
        $fileManagerExpectsOnce = $fileManager->expects($this->once());
        $fileManagerExpectsOnce->method('fileGetContent');
        $fileManagerExpectsOnce->with($url);
        $fileManagerExpectsOnce->will($this->returnValue($result));

        $sUT = new WebDriver($fileManager);
        $driverConfig = new DriverConfig('my name');
        $driverConfig->response('configUrl', $url);

        $this->assertEquals($result, $sUT->getFile($driverConfig));
    }
}
