<?php
namespace CrudGenerator\Tests\General\Generators\Questions\GeneratorQuestionFactory;

use CrudGenerator\Generators\Questions\GeneratorQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\GeneratorQuestion',
            GeneratorQuestionFactory::getInstance($context)
        );
    }
}
