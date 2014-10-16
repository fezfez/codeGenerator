<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetaDataSourcesQuestionFactory;

use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion',
            MetaDataSourcesQuestionFactory::getInstance($context)
        );
    }
}
