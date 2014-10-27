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
use CrudGenerator\Tests\TestCase;

class SaveTest extends TestCase
{
    public function testSaveOk()
    {
        $rawMocks = $this->createSut('CrudGenerator\MetaData\Config\MetaDataConfigDAO');

        $url = 'http://myjson.com/2tkfh';

        $contextExcepectAsk = $rawMocks['mocks']['context']->expects($this->once());
        $contextExcepectAsk->method("ask");
        $contextExcepectAsk->willReturn($url);

        $fileManagerFilePutContent = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerFilePutContent->method("filePutsContent");

        /* @var $sUT \CrudGenerator\MetaData\Config\MetaDataConfigDAO */
        $sUT    = $rawMocks['instance']($rawMocks['mocks']);
        $result = $sUT->save(JsonMetaDataDAOFactory::getDescription());

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\MetaDataSource',
            $result
        );

        $this->assertEquals($url, $result->getConfig()->getResponse('configUrl'));
    }
}
