<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParserFactory;

use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\GeneratorParser',
            GeneratorParserFactory::getInstance($context)
        );
    }
}
