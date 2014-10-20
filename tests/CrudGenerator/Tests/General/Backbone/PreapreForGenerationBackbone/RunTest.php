<?php
namespace CrudGenerator\Tests\General\Backbone\PreapreForGenerationBackbone;

use CrudGenerator\Backbone\PreapreForGenerationBackbone;

class RunTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerator()
    {
        $metadataSourceConfigured = $this->createMock(
            'CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion'
        );
        $metadataQuestion = $this->createMock(
            'CrudGenerator\Generators\Questions\Metadata\MetadataQuestion'
        );
        $generatorQuestion = $this->createMock(
            'CrudGenerator\Generators\Questions\Generator\GeneratorQuestion'
        );
        $generatorParser = $this->createMock(
            'CrudGenerator\Generators\Parser\GeneratorParser'
        );

        $source   = new \CrudGenerator\MetaData\MetaDataSource();
        $metadata = $this->createMock('CrudGenerator\MetaData\DataObject\MetaData');

        $metadataSourceConfigured->expects($this->once())
        ->method('ask')
        ->willReturn($source);

        $metadataQuestion->expects($this->once())
        ->method('ask')
        ->with($source)
        ->willReturn($metadata);

        $generatorQuestion->expects($this->once())
        ->method('ask')
        ->with($metadata)
        ->willReturn('generator');

        $generatorParser->expects($this->once())
        ->method('init')
        ->willReturnArgument(0);

        $sUT = new PreapreForGenerationBackbone(
            $metadataSourceConfigured,
            $metadataQuestion,
            $generatorQuestion,
            $generatorParser
        );

        $this->assertInstanceOf(
            'CrudGenerator\Generators\GeneratorDataObject',
            $sUT->run()
        );
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
