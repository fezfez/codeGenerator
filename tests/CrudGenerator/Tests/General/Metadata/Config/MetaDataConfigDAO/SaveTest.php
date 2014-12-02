<?php
namespace CrudGenerator\Tests\General\Metadata\Config\MetaDataConfigDAO;

use CrudGenerator\Metadata\Config\MetaDataConfigDAO;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\Tests\TestCase;

class SaveTest extends TestCase
{
    public function testSaveOk()
    {
        $rawMocks = $this->createSut('CrudGenerator\Metadata\Config\MetaDataConfigDAO');

        $url = 'http://myjson.com/2tkfh';

        $contextExcepectAsk = $rawMocks['mocks']['context']->expects($this->once());
        $contextExcepectAsk->method("ask");
        $contextExcepectAsk->willReturn($url);

        $fileManagerFilePutContent = $rawMocks['mocks']['fileManager']->expects($this->once());
        $fileManagerFilePutContent->method("filePutsContent");

        /* @var $sUT \CrudGenerator\Metadata\Config\MetaDataConfigDAO */
        $sUT    = $rawMocks['instance']($rawMocks['mocks']);
        $result = $sUT->save(JsonMetaDataDAOFactory::getDescription());

        $this->assertInstanceOf(
            'CrudGenerator\Metadata\MetaDataSource',
            $result
        );

        $this->assertEquals($url, $result->getConfig()->getResponse('configUrl'));
    }
}
