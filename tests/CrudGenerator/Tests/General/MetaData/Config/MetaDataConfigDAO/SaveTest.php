<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigDAO;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Driver;
use CrudGenerator\MetaData\Driver\DriverValidator;
use CrudGenerator\Utils\Transtyper;
use CrudGenerator\MetaData\MetaDataSourceValidator;
use CrudGenerator\MetaData\Driver\DriverHydrator;
use CrudGenerator\Utils\ComparatorFactory;
use CrudGenerator\Utils\TranstyperFactory;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveOk()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');

        $url = 'http://myjson.com/2tkfh';
        $context->expects($this->once())
        ->method("ask")
        ->willReturn($url);

        $config = new DriverConfig("im am unique !");
        $config->addQuestion('Url', 'configUrl');
        $config->setDriver(__CLASS__);

        $driver = new Driver();
        $driver->setConfig($config)
        ->setDefinition('Web connector')
        ->setUniqueName('Web');

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Json adapter")
        ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory')
        ->setMetadataDao("CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO")
        ->addDriverDescription($driver)
        ->setUniqueName('Json');

        $sUT = new MetaDataConfigDAO(
            $fileManager,
            TranstyperFactory::getInstance(),
            \KeepUpdate\ArrayValidatorFactory::getInstance(),
            new MetaDataSourceHydrator(),
            new DriverHydrator(),
            $context
        );

        $result = $sUT->save(JsonMetaDataDAOFactory::getDescription());

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            $result
        );

        $this->assertEquals($url, $result->getConfig()->getResponse('configUrl'));
    }

    public function testSaveFail()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');

        $url = 'http://myurl.org';
        $context->expects($this->once())
        ->method("ask")
        ->willReturn($url);

        $config = new DriverConfig("im am unique !");
        $config->addQuestion('Url', 'configUrl');
        $config->setDriver(__CLASS__);

        $driver = new Driver();
        $driver->setConfig($config)
        ->setDefinition('Web connector')
        ->setUniqueName('Web');

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Json adapter")
        ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory')
        ->setMetadataDao("CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO")
        ->addDriverDescription($driver)
        ->setUniqueName('Json');

        $sUT = new MetaDataConfigDAO(
            $fileManager,
            TranstyperFactory::getInstance(),
            \KeepUpdate\ArrayValidatorFactory::getInstance(),
            new MetaDataSourceHydrator(),
            new DriverHydrator(),
            $context
        );

        $result = $sUT->ask($dataObject);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            $result
        );

        $this->assertEquals($url, $result->getConfig()->getResponse('configUrl'));
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
