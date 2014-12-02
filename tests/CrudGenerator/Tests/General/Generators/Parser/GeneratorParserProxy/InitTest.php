<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParserProxy;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParserProxy;
use CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Generators\Parser\ParserCollection;
use CrudGenerator\Tests\TestCase;

class InitTest extends TestCase
{
    public function testEmpty()
    {
        $context   = $this->createMock('CrudGenerator\Context\CliContext');
        $sUT       = new GeneratorParserProxy($context);
        $generator = new GeneratorDataObject();
        $metadata  = new MetadataDataObjectPostgreSQL(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        // The only way to quickly test the proxy is to generate an error
        $this->setExpectedException('InvalidArgumentException');

        $sUT->init($generator, $metadata);
    }
}
