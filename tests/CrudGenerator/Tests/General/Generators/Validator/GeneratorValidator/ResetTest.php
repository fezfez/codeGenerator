<?php
namespace CrudGenerator\Tests\General\Generators\Validator\GeneratorValidator;

use CrudGenerator\Generators\Validator\GeneratorValidator;

class ResetTest extends \PHPUnit_Framework_TestCase
{
    public function testWithValidSchema()
    {
        $validator = $this->getMockBuilder('JsonSchema\Validator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $validator->expects($this->once())
                  ->method('reset')
                  ->will($this->returnValue(true));

        $sUT = new GeneratorValidator('', $validator);

        $sUT->reset();
    }
}
