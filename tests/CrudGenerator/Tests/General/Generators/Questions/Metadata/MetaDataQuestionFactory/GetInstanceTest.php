<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Metadata\MetadataQuestionFactory;

use CrudGenerator\Generators\Questions\Metadata\MetadataQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Metadata\MetadataQuestion',
            MetadataQuestionFactory::getInstance($context)
        );
    }
}
