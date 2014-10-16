<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetaDataQuestionFactory;

use CrudGenerator\Generators\Questions\MetaDataQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\MetaDataQuestion',
            MetaDataQuestionFactory::getInstance($context)
        );
    }
}
