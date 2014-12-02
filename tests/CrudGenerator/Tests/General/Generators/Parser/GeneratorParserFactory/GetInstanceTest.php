<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParserFactory;

use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;

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
