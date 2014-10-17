<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Directory\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Directory\DirectoryQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Directory\DirectoryQuestion',
            DirectoryQuestionFactory::getInstance($context)
        );
    }
}
