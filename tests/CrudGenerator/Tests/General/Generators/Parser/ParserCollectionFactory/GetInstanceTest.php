<?php
namespace CrudGenerator\Tests\General\Generators\Parser\ParserCollectionFactory;

use CrudGenerator\Generators\Parser\ParserCollectionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testCliInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\ParserCollection',
            ParserCollectionFactory::getInstance($context)
        );
    }
}
