<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigDAO;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\DriverConfig;

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

        $sUT = new MetaDataConfigDAO(new ClassAwake(), $fileManager, new MetaDataSourceHydrator(), $context);

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

        $sUT = new MetaDataConfigDAO(new ClassAwake(), $fileManager, new MetaDataSourceHydrator(), $context);

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

        $sUT = new MetaDataConfigDAO(new ClassAwake(), $fileManager, new MetaDataSourceHydrator(), $context);

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
            MetaDataConfigDAO::RESPONSE_KEY => array(
                'configUrl' => 'here'
            ),
            MetaDataSource::METADATA_DAO_FACTORY=> 'CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory',
            MetaDataSource::UNIQUE_NAME => 'test',
            MetaDataSource::METADATA_DAO_FACTORY => 'CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory'
        );

        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(json_encode($data)));

        $sUT = new MetaDataConfigDAO(new ClassAwake(), $fileManager, new MetaDataSourceHydrator(), $context);

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
}q
