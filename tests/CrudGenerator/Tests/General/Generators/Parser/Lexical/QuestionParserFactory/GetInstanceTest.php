<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\QuestionParserFactory;


use CrudGenerator\Generators\Parser\Lexical\QuestionParserFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
    	$context = $this->getMockForAbstractClass('CrudGenerator\Context\ContextInterface');

    	$this->setExpectedException('InvalidArgumentException');

    	QuestionParserFactory::getInstance($context);
    }
}
