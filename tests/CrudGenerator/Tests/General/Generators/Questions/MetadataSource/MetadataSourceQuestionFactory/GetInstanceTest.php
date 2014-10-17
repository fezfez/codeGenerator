<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetadataSourceQuestionFactory;

use CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion',
            MetadataSourceQuestionFactory::getInstance($context)
        );
    }
}
