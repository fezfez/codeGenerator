<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParser;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\ParserCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\Tests\TestCase;

class InitTest extends TestCase
{
    public function testEmpty()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $transtyper         = $this->createMock('CrudGenerator\Utils\Transtyper');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $generatorFinder    = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');
        $generatorValidator = $this->createMock('CrudGenerator\Generators\Validator\GeneratorValidator');
        $parserCollection   = new ParserCollection();

        $process = array(
            'dto'  => 'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect',
            'name' => 'test',
        );

        $transtyperExpects = $transtyper->expects($this->once());
        $transtyperExpects->method('decode');
        $transtyperExpects->will($this->returnValue($process));

        $sUT       = new GeneratorParser(
            $fileManager,
            $transtyper,
            $phpParser,
            $generatorFinder,
            $parserCollection,
            $generatorValidator
        );
        $generator = new GeneratorDataObject();
        $metadata  = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }

    public function testWithPreParseAndPostParse()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $transtyper         = $this->createMock('CrudGenerator\Utils\Transtyper');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $generatorFinder    = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');
        $generatorValidator = $this->createMock('CrudGenerator\Generators\Validator\GeneratorValidator');
        $questionResponse   = $this->createMock('CrudGenerator\Generators\Parser\Lexical\QuestionParser');
        $generator          = new GeneratorDataObject();
        $parserCollection   = new ParserCollection();

        $questionResponseExcpects = $questionResponse->expects($this->once());
        $questionResponseExcpects->method('evaluate');
        $questionResponseExcpects->will($this->returnValue($generator));

        $parserCollection->addPreParse($questionResponse);

        $process = array(
            'dto'  => 'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect',
            'name' => 'test',
        );

        $transtyperExpects = $transtyper->expects($this->once());
        $transtyperExpects->method('decode');
        $transtyperExpects->will($this->returnValue($process));

        $sUT      = new GeneratorParser(
            $fileManager,
            $transtyper,
            $phpParser,
            $generatorFinder,
            $parserCollection,
            $generatorValidator
        );
        $metadata = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }
}
