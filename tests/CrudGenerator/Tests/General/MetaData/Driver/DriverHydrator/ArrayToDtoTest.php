<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\DriverHydrator;

use CrudGenerator\MetaData\Driver\DriverHydrator;
use CrudGenerator\MetaData\Driver\DriverConfig;

class ArrayToDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrate()
    {
        $sUT = new DriverHydrator();

        $data = array(
            DriverConfig::UNIQUE_NAME => 'myName',
            DriverConfig::RESPONSE => array(
                'myKey' => 'myVal'
            ),
            DriverConfig::FACTORY => 'CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory'
        );

        $results = $sUT->arrayToDto($data);

        $this->assertInstanceOf('CrudGenerator\MetaData\Driver\DriverConfig', $results);

        $this->assertEquals($data[DriverConfig::RESPONSE]['myKey'], $results->getResponse('myKey'));

        $this->assertEquals($data[DriverConfig::UNIQUE_NAME], $results->getUniqueName());
        $this->assertEquals($data[DriverConfig::FACTORY], $results->getDriver());
    }
}