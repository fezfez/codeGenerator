<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParser;

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

        $yaml =  $this->getMockBuilder('CrudGenerator\Utils\Transtyper')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorFinder =  $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorCompatibilityCheckerMock = $this->getMockBuilder('CrudGenerator\Generators\GeneratorCompatibilityChecker')
        ->disableOriginalConstructor()
        ->getMock();

        $parserCollection =  new \CrudGenerator\Generators\Parser\ParserCollection();

        $process = array(
            'dto' => 'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect',
            'name' => 'test'
        );

        $yaml->expects($this->once())
        ->method('decode')
        ->will($this->returnValue($process));

        $sUT       = new GeneratorParser($fileManager, $yaml, $phpParser, $generatorFinder, $parserCollection, $generatorCompatibilityCheckerMock);
        $generator = new GeneratorDataObject();
        $metadata  = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }

    public function testWithPreParseAndPostParse()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $yaml =  $this->getMockBuilder('CrudGenerator\Utils\Transtyper')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorFinder =  $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorCompatibilityCheckerMock = $this->getMockBuilder('CrudGenerator\Generators\GeneratorCompatibilityChecker')
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
        ->method('decode')
        ->will($this->returnValue($process));

        $sUT       = new GeneratorParser($fileManager, $yaml, $phpParser, $generatorFinder, $parserCollection, $generatorCompatibilityCheckerMock);
        $metadata  = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }
}
