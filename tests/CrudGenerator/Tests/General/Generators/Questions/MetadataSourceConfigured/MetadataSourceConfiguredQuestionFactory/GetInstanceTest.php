<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestionFactory;

use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion',
            MetadataSourceConfiguredQuestionFactory::getInstance($context)
        );
    }
}
