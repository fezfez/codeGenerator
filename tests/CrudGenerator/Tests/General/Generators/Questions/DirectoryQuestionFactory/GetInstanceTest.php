<?php
namespace CrudGenerator\Tests\General\Generators\Questions\DirectoryQuestion;

use CrudGenerator\Generators\Questions\DirectoryQuestionFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\DirectoryQuestion',
            DirectoryQuestionFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\DirectoryQuestion',
            DirectoryQuestionFactory::getInstance($context)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        DirectoryQuestionFactory::getInstance($context);
    }
}
