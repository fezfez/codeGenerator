<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\History\HistoryHydrator;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use CrudGenerator\Generators\Questions\Cli\MetaDataSourcesQuestion;
use CrudGenerator\Generators\Questions\Cli\MetaDataQuestion;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;

class DtoToYamlTest extends \PHPUnit_Framework_TestCase
{
    public function testWithInvalidHistory()
    {
        $stubDumper = $this->getMockBuilder('Symfony\Component\Yaml\Dumper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubParser = $this->getMockBuilder('Symfony\Component\Yaml\Parser')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSourceQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\MetaDataSourcesQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubDumper, $stubParser, $stubMetadataSourceQuestion, $stubMetadataSource);

        $dataObject = new Architect();

        $this->setExpectedException('CrudGenerator\History\InvalidHistoryException');

        $sUT->dtoToYaml($dataObject);
    }

    public function testOk()
    {
        $stubDumper = $this->getMockBuilder('Symfony\Component\Yaml\Dumper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubParser = $this->getMockBuilder('Symfony\Component\Yaml\Parser')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSourceQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\MetaDataSourcesQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubDumper, $stubParser, $stubMetadataSourceQuestion, $stubMetadataSource);

        $stubDumper->expects($this->once())
        ->method('dump');

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $dataObject = new Architect();
        $dataObject->setMetadata($metaData);

        $sUT->dtoToYaml($dataObject);
    }
}
