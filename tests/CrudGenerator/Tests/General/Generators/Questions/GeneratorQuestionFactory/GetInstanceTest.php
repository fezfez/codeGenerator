<?php
namespace CrudGenerator\Tests\General\Command\Questions\GeneratorQuestion;

use CrudGenerator\Generators\Questions\GeneratorQuestionFactory;
use CrudGenerator\Context\CliContext;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCli()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\GeneratorQuestion',
            GeneratorQuestionFactory::getInstance($context)
        );
    }

    public function testInstanceWeb()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\GeneratorQuestion',
            GeneratorQuestionFactory::getInstance($context)
        );
    }

    public function testFailContext()
    {
        $context =  $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        GeneratorQuestionFactory::getInstance($context);
    }
}
