<?php
namespace CrudGenerator\Tests\General\Backbone\CreateSourceBackbone;

use CrudGenerator\Backbone\CreateSourceBackbone;
use CrudGenerator\MetaData\MetaDataSource;

class RunTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveError()
    {
        $context        = $this->createMock('CrudGenerator\Context\CliContext');
        $configDao      = $this->createMock('CrudGenerator\MetaData\Config\MetaDataConfigDAO');
        $metadataSource = $this->createMock('CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion');

        $metadataSourceDto = new MetaDataSource();

        $metadataSource->expects($this->once())
        ->method('ask')
        ->willReturn($metadataSourceDto);

        $configDao->expects($this->once())
        ->method('save')
        ->willThrowException(new \CrudGenerator\MetaData\Config\ConfigException());

        $sUT = new CreateSourceBackbone($metadataSource, $configDao, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->run();
    }

    public function testSaveOk()
    {
        $contextStub    = $this->createMock('CrudGenerator\Context\CliContext');
        $configDaoStub  = $this->createMock('CrudGenerator\MetaData\Config\MetaDataConfigDAO');
        $metadataSource = $this->createMock('CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion');

        $metadataSourceDto = new MetaDataSource();

        $metadataSource->expects($this->once())
        ->method('ask')
        ->willReturn($metadataSourceDto);

        $configDaoStub->expects($this->once())
        ->method('save');

        $sUT = new CreateSourceBackbone($metadataSource, $configDaoStub, $contextStub);

        $sUT->run();
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