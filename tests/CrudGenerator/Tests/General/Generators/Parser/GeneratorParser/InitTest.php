<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParser;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class InitTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptydddd()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $yaml =  $this->getMockBuilder('CrudGenerator\Utils\Yaml')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $viewFile =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorFinder =  $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $parserCollection =  new \CrudGenerator\Generators\Parser\ParserCollection();

        $process = array(
            'dto' => 'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect',
            'name' => 'test'
        );

        $yaml->expects($this->once())
        ->method('parse')
        ->will($this->returnValue($process));

        $sUT       = new GeneratorParser($fileManager, $yaml, $phpParser, $viewFile, $generatorFinder, $parserCollection);
        $generator = new GeneratorDataObject();
        $metadata  = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }

    public function testWithPreParseAndPostParse()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $yaml =  $this->getMockBuilder('CrudGenerator\Utils\Yaml')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $viewFile =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorFinder =  $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $generator = new GeneratorDataObject();
        $parserCollection =  new \CrudGenerator\Generators\Parser\ParserCollection();

        $questionResponse =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\QuestionParser')
        ->disableOriginalConstructor()
        ->getMock();

        $questionResponse->expects($this->once())
        ->method('evaluate')
        ->will($this->returnValue($generator));

        $parserCollection->addPreParse($questionResponse);


        $process = array(
            'dto' => 'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect',
            'name' => 'test'
        );

        $yaml->expects($this->once())
        ->method('parse')
        ->will($this->returnValue($process));

        $sUT       = new GeneratorParser($fileManager, $yaml, $phpParser, $viewFile, $generatorFinder, $parserCollection);
        $metadata  = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }
}
