<?php
namespace CrudGenerator\Tests\General\Metadata\Driver\DriverHydrator;

use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\DriverHydrator;

class ArrayToDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrate()
    {
        $sUT = new DriverHydrator();

        $data = array(
            DriverConfig::UNIQUE_NAME => 'myName',
            DriverConfig::RESPONSE    => array(
                'myName' => 'myVal',
            ),
            DriverConfig::FACTORY => 'CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory',
            DriverConfig::SOURCE_FACTORY => '\CrudGenerator\Metadata\Sources\MySQL\MySQLMetaDataDAOFactory',
        );

        $results = $sUT->arrayToDto($data);

        $this->assertInstanceOf('CrudGenerator\Metadata\Driver\DriverConfig', $results);

        $this->assertEquals($data[DriverConfig::RESPONSE]['myName'], $results->getResponse('myName'));

        // $this->assertEquals($data[DriverConfig::UNIQUE_NAME], $results->getUniqueName()); ???
        $this->assertEquals(' myVal', $results->getUniqueName());
        $this->assertEquals($data[DriverConfig::FACTORY], $results->getDriver());
    }
}
