<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\File\Web\WebDriver;

use CrudGenerator\Metadata\Driver\File\Web\WebDriver;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Metadata\Driver\DriverConfig;

class GetUniqueNameTest extends TestCase
{
    public function testOk()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');

        $url = 'toto';

        $sUT = new WebDriver($fileManager);

        $driverConfig = new DriverConfig('my name');
        $driverConfig->response('configUrl', $url);

        $this->assertEquals($url, $sUT->getUniqueName($driverConfig));
    }
}