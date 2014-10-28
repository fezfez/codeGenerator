<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParserProxyFactory;

use CrudGenerator\Generators\Parser\GeneratorParserProxyFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $context = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\GeneratorParserProxy',
            GeneratorParserProxyFactory::getInstance($context)
        );
    }
}
