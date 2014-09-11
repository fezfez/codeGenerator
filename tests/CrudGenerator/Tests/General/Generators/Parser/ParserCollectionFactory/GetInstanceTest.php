<?php
namespace CrudGenerator\Tests\General\Generators\Parser\ParserCollectionFactory;

use CrudGenerator\Generators\Parser\ParserCollectionFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testCliInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\ParserCollection',
            ParserCollectionFactory::getInstance($context)
        );
    }

    public function testWebInstance()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\ParserCollection',
            ParserCollectionFactory::getInstance($context)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        ParserCollectionFactory::getInstance($context);
    }
}
