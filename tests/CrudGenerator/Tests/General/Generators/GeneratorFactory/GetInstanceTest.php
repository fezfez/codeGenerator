<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFactory;

use CrudGenerator\Generators\GeneratorFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $stategy = $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Generator',
            GeneratorFactory::getInstance($context, $stategy)
        );
    }
}
