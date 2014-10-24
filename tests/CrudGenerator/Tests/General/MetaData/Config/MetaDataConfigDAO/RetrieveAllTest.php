<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigDAO;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\Utils\TranstyperFactory;
use KeepUpdate\ArrayValidatorFactory;
use CrudGenerator\MetaData\Driver\DriverHydrator;

class RetrieveAllTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutResponseKey()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');

        $fileManager->expects($this->once())
        ->method('glob')
        ->will($this->returnValue(array('myFile')));

        $data = array(MetaDataSource::METADATA_DAO_FACTORY => 'nonexistMetadata');
        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(json_encode($data)));

        $sUT = new MetaDataConfigDAO(
            $fileManager,
            TranstyperFactory::getInstance(),
            ArrayValidatorFactory::getInstance(),
            new MetaDataSourceHydrator(),
            new DriverHydrator(),
            $context
        );

        $results = $sUT->retrieveAll();

        $this->assertCount(0, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

    public function testWithoutSourceFactoryKey()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');

        $fileManager->expects($this->once())
        ->method('glob')
        ->will($this->returnValue(array('myFile')));

        $data = array(DriverConfig::RESPONSE => 'im');
        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(json_encode($data)));

        $sUT = new MetaDataConfigDAO(
            $fileManager,
            TranstyperFactory::getInstance(),
            ArrayValidatorFactory::getInstance(),
            new MetaDataSourceHydrator(),
            new DriverHydrator(),
            $context
        );

        $results = $sUT->retrieveAll();

        $this->assertCount(0, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

    public function testWithSourceFactoryKeyThatDoesNotExist()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');

        $fileManager->expects($this->once())
        ->method('glob')
        ->will($this->returnValue(array('myFile')));

        $data = array(
            DriverConfig::RESPONSE => 'im',
            MetaDataSource::METADATA_DAO_FACTORY => 'nonexistMetadata'
        );

        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(json_encode($data)));

        $sUT = new MetaDataConfigDAO(
            $fileManager,
            TranstyperFactory::getInstance(),
            ArrayValidatorFactory::getInstance(),
            new MetaDataSourceHydrator(),
            new DriverHydrator(),
            $context
        );

        $results = $sUT->retrieveAll();

        $this->assertCount(0, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

    public function testOk()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');

        $fileManager->expects($this->once())
        ->method('glob')
        ->will($this->returnValue(array('myFile')));

        $data = array(
            MetaDataSource::CONFIG => array(
                DriverConfig::RESPONSE => array(
                    'configUrl' => 'here'
                ),
                DriverConfig::SOURCE_FACTORY => 'CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory',
                DriverConfig::FACTORY => 'CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory',
                DriverConfig::UNIQUE_NAME => 'bbb'
            ),
            MetaDataSource::METADATA_DAO_FACTORY => 'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory',
            MetaDataSource::METADATA_DAO => '',
            MetaDataSource::FALSE_DEPENDENCIES => '',
            MetaDataSource::UNIQUE_NAME => 'test',
            MetaDataSource::METADATA_DAO_FACTORY => 'CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory',
            MetaDataSource::DEFINITION => ''
        );

        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(json_encode($data)));

        $sUT = new MetaDataConfigDAO(
            $fileManager,
            TranstyperFactory::getInstance(),
            ArrayValidatorFactory::getInstance(),
            new MetaDataSourceHydrator(),
            new DriverHydrator(),
            $context
        );

        $results = $sUT->retrieveAll();

        $this->assertCount(1, $results);
        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }

    /**
     * @param string $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createMock($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}
