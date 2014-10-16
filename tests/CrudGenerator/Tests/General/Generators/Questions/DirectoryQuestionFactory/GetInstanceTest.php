<?php
namespace CrudGenerator\Tests\General\Generators\Questions\DirectoryQuestion;

use CrudGenerator\Generators\Questions\DirectoryQuestionFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\DirectoryQuestion',
            DirectoryQuestionFactory::getInstance($context)
        );
    }
}
