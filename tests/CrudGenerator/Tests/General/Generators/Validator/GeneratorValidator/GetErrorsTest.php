<?php
namespace CrudGenerator\Tests\General\Generators\Validator\GeneratorValidator;

use CrudGenerator\Generators\Validator\GeneratorValidator;

class GetErrorsTest extends \PHPUnit_Framework_TestCase
{
    public function testWithValidSchema()
    {
        $validator = $this->getMockBuilder('JsonSchema\Validator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $error = array('an error');
        $validator->expects($this->once())
                  ->method('getErrors')
                  ->will($this->returnValue($error));

        $sUT = new GeneratorValidator('', $validator);

        $this->assertEquals($error, $sUT->getErrors());
    }
}
