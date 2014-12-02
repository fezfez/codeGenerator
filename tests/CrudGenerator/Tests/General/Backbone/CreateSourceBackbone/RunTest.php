<?php
namespace CrudGenerator\Tests\General\Backbone\CreateSourceBackbone;

use CrudGenerator\Backbone\CreateSourceBackbone;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Tests\TestCase;

class RunTest extends TestCase
{
    public function testCreateSource()
    {
        $rawMock = $this->createSut('CrudGenerator\Backbone\CreateSourceBackbone');

        $source = new MetaDataSource();

        $metadataSourceQuestion = $rawMock['mocks']['metadataSourceQuestion']->expects($this->once());
        $metadataSourceQuestion->method('ask');
        $metadataSourceQuestion->willReturn($source);

        $metadataConfigDAO = $rawMock['mocks']['metadataConfigDAO']->expects($this->once());
        $metadataConfigDAO->method('save');
        $metadataConfigDAO->with($source);

        $context = $rawMock['mocks']['context']->expects($this->once());
        $context->method('log');

        /* @var $sUT \CrudGenerator\Backbone\CreateSourceBackbone */
        $sUT = $rawMock['instance']($rawMock['mocks']);

        $sUT->run();
    }
}
