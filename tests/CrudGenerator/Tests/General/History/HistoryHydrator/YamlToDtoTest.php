<?php
namespace CrudGenerator\Tests\General\History\HistoryHydrator;

use CrudGenerator\History\HistoryHydrator;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use CrudGenerator\Command\Questions\MetaDataSourcesQuestion;
use CrudGenerator\Command\Questions\MetaDataQuestion;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;

class YamlToDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $stubDumper = $this->getMockBuilder('Symfony\Component\Yaml\Dumper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubParser = new Parser;

        $stubMetadataSourceQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubDumper, $stubParser, $stubMetadataSourceQuestion, $stubMetadataSource);

        $metaDataSource = 'CrudGenerator\MetaData\MetaDataSource';
        $metaDataName = 'Corp\NewsEntity';

        $stubMetadataSourceClass = $this->getMockBuilder($metaDataSource)
        ->disableOriginalConstructor()
        ->getMock();

        $metaData = new MetadataDataObjectDoctrine2(
                        new MetaDataColumnCollection(),
                        new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $stubMetadataSourceQuestion->expects($this->once())
        ->method('ask')
        ->with($this->equalTo($metaDataSource))
        ->will($this->returnValue($stubMetadataSourceClass));

        $stubMetadataSource->expects($this->once())
        ->method('ask')
        ->with($this->equalTo($stubMetadataSourceClass), $this->equalTo($metaDataName))
        ->will($this->returnValue($metaData));

        $yaml = "module: ./module/Application/
metaDataSource: " . $metaDataSource . "
metaData: " . $metaDataName . "
Generators:
    CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud:
        options:
            AttributeName:
                id: Id
                dtCreat: 'Date de création'
                nomLog: 'Nom log'
            WriteAction: NewsEntity
            PrefixRouteName: news
            DisplayName: news
            DisplayNames: news
            ModelNamespace: Corp
            ControllerName: News
            ControllerNamespace: Application";

        $this->assertInstanceOf('CrudGenerator\History\History', $sUT->jsonToDto($yaml));
    }
}
