<?php
namespace CrudGenerator\Tests\General\History\HistoryHydrator;

use CrudGenerator\History\HistoryHydrator;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;
use CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO;

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

        $stubMetadataSourceQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubDumper, $stubParser, $stubMetadataSourceQuestion, $stubMetadataSource);

        $dataObject = new Architect();
        $generator = new GeneratorDataObject();
        $generator->setDTO($dataObject);
        $this->setExpectedException('CrudGenerator\History\InvalidHistoryException');

        $sUT->dtoToJson($generator);
    }

    public function testOk()
    {
        $stubDumper = $this->getMockBuilder('Symfony\Component\Yaml\Dumper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubParser = $this->getMockBuilder('Symfony\Component\Yaml\Parser')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSourceQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubDumper, $stubParser, $stubMetadataSourceQuestion, $stubMetadataSource);

        $source = new MetaDataSource();
        $source->setConfig(new MySQLConfig())
        ->setMetaDataDAO("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO")
        ->setMetaDataDAOFactory("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory");

        $metaData = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $dataObject = new Architect();
        $dataObject->setMetadata($metaData);

        $generator = new GeneratorDataObject();
        $generator->setDTO($dataObject)
                  ->setMetadataSource($source);

        $sUT->dtoToJson($generator);
    }

    public function testBoth()
    {
        $stubDumper = $this->getMockBuilder('Symfony\Component\Yaml\Dumper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubParser = $this->getMockBuilder('Symfony\Component\Yaml\Parser')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSourceQuestion = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $stubMetadataSource = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new HistoryHydrator($stubDumper, $stubParser, $stubMetadataSourceQuestion, $stubMetadataSource);

        $metaData = new MetadataDataObjectDoctrine2(
                new MetaDataColumnCollection(),
                new MetaDataRelationCollection()
        );
        $metaData->setName('MyName');

        $source = new MetaDataSource();
        $source->setConfig(new MySQLConfig())
               ->setMetaDataDAO("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO")
               ->setMetaDataDAOFactory("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory");

        $stubMetadataSourceQuestion->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($source));

        $stubMetadataSource->expects($this->once())
        ->method('ask')
        ->with($source)
        ->will($this->returnValue($metaData));

        $dataObject = new Architect();
        $dataObject->setMetadata($metaData);

        $generator = new GeneratorDataObject();
        $generator->setMetadataSource($source)
                  ->setDTO($dataObject);

        $yaml = $sUT->dtoToJson($generator);
        $sUT->jsonToDto($yaml);
    }
}
