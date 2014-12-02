<?php
namespace CrudGenerator\Tests\General\Metadata\Config\MetaDataConfigDAO;

use CrudGenerator\Generators\ResponseExpectedException;
use CrudGenerator\Metadata\Config\MetaDataConfigDAO;
use CrudGenerator\Metadata\Driver\Driver;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    /**
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    private function getMetadataSource()
    {
        $config = new DriverConfig("im am unique !");
        $config->addQuestion('Url', 'configUrl');
        $config->setDriver('CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory');

        $driver = new Driver();
        $driver->setConfig($config);
        $driver->setDefinition('Web connector');
        $driver->setUniqueName('Web');

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Json adapter");
        $dataObject->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory');
        $dataObject->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO");
        $dataObject->addDriverDescription($driver);
        $dataObject->setUniqueName('Json');

        return $dataObject;
    }

    public function testWithOneDriver()
    {
        $rawMocks       = $this->createSut('CrudGenerator\Metadata\Config\MetaDataConfigDAO');
        $url            = 'http://myurl.org';
        $contextExpects = $rawMocks['mocks']['context']->expects($this->exactly(2));

        $contextExpects->method("ask");
        $contextExpects->willReturnOnConsecutiveCalls($url, $this->throwException(new ResponseExpectedException()));

        $sUT = $rawMocks['instance']($rawMocks['mocks']);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($this->getMetadataSource());
    }

    public function testWithMultipleDriver()
    {
        $config = new DriverConfig("im am unique !");
        $config->addQuestion('Url', 'configUrl');
        $config->setDriver('CrudGenerator\Metadata\Driver\Pdo\PdoDriverFactory');

        $driver = new Driver();
        $driver->setConfig($config);
        $driver->setDefinition('Web connector');
        $driver->setUniqueName('Web');

        $driverTwo = new Driver();
        $driverTwo->setConfig($config);
        $driverTwo->setDefinition('Web connector two');
        $driverTwo->setUniqueName('Web two');

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Json adapter");
        $dataObject->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory');
        $dataObject->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO");
        $dataObject->addDriverDescription($driver);
        $dataObject->addDriverDescription($driverTwo);
        $dataObject->setUniqueName('Json');

        $rawMocks                    = $this->createSut('CrudGenerator\Metadata\Config\MetaDataConfigDAO');
        $url                         = 'http://myurl.org';

        $contextExpectsAsk = $rawMocks['mocks']['context']->expects($this->exactly(2));
        $contextExpectsAsk->method("ask");
        $contextExpectsAsk->will($this->onConsecutiveCalls($url, $this->throwException(new ResponseExpectedException())));

        $contextExpectsAskCollection = $rawMocks['mocks']['context']->expects($this->exactly(2));
        $contextExpectsAskCollection->method("askCollection");
        $contextExpectsAskCollection->willReturn($driver);

        /* @var $sUT \CrudGenerator\Metadata\Config\MetaDataConfigDAO */
        $sUT = $rawMocks['instance']($rawMocks['mocks']);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask($dataObject);
    }
}
