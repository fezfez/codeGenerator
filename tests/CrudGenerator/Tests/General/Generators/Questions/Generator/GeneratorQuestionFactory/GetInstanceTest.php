<?php
namespace CrudGenerator\Tests\General\Generators\Generator\GeneratorQuestionFactory;

use CrudGenerator\Generators\Questions\Generator\GeneratorQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Generator\GeneratorQuestion',
            GeneratorQuestionFactory::getInstance($context)
        );
    }
}
