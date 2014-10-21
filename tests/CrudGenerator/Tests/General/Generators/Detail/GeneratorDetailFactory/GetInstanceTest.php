<?php
namespace CrudGenerator\Tests\General\Generators\Detail\GeneratorDetail;

use CrudGenerator\Generators\Detail\GeneratorDetailFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Detail\GeneratorDetail',
            GeneratorDetailFactory::getInstance($context)
        );
    }
}
