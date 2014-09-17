<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetaDataQuestion;

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

    public function testInstanceWeb()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\Questions\Web\MetaDataQuestion',
            MetaDataQuestionFactory::getInstance($context)
        );
    }

    public function testFail()
    {
        $context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

        $this->setExpectedException('InvalidArgumentException');

        MetaDataQuestionFactory::getInstance($context);
    }
}
