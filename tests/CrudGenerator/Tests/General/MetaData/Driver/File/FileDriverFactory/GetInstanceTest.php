<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\File\FileDriverFactory;

use CrudGenerator\Metadata\Driver\File\FileDriverFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testWithClassDoesNotExist()
    {
        $driverConfig = new DriverConfig("name");

        $this->setExpectedException('Exception');

        FileDriverFactory::getInstance($driverConfig);
    }

    public function testResultIsNotCorrectInterface()
    {
        $driverConfig = new DriverConfig("name");
        $driverConfig->setDriver('CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory');

        $this->setExpectedException('Exception');

        FileDriverFactory::getInstance($driverConfig);
    }

    public function testOk()
    {
        $driverConfig = new DriverConfig("name");
        $driverConfig->setDriver('CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Driver\File\FileDriverInterface',
            FileDriverFactory::getInstance($driverConfig)
        );
    }
}