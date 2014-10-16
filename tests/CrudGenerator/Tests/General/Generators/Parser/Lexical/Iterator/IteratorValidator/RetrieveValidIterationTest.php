<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Iterator\IteratorValidator;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidatorFactory;

class RetrieveValidIterationTest extends \PHPUnit_Framework_TestCase
{
    public function testFailWithInvalidIterator()
    {
        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $fakeIterator = 'fake';

        $phpParser->expects($this->once())
        ->method('staticPhp')
        ->with($fakeIterator)
        ->will($this->returnValue(null));

        $generator = new GeneratorDataObject();
        $condition = ConditionValidatorFactory::getInstance();

        $sUT = new IteratorValidator($condition);

        $node = array(
            'iteration' => array(
                'iterator' => $fakeIterator
            )
        );

        $this->setExpectedException('InvalidArgumentException');

        $sUT->retrieveValidIteration($node, $generator, $phpParser);
    }

    public function testWithoutCondition()
    {
        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $fakeIterator  = 'fake';
        $returnedValue = array('myValue!');

        $phpParser->expects($this->once())
        ->method('staticPhp')
        ->with($fakeIterator)
        ->will($this->returnValue($returnedValue));

        $generator = new GeneratorDataObject();
        $condition = ConditionValidatorFactory::getInstance();

        $sUT = new IteratorValidator($condition);

        $node = array(
            'iteration' => array(
                'iterator' => $fakeIterator
            )
        );

        $this->assertEquals($returnedValue, $sUT->retrieveValidIteration($node, $generator, $phpParser));
    }
}
