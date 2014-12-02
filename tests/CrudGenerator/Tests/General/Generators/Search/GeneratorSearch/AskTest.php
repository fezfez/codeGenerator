<?php
namespace CrudGenerator\Tests\General\Generators\Search\GeneratorSearch;

use CrudGenerator\Generators\Search\GeneratorSearch;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testSearch()
    {
        $packagistApi = $this->getMockWithoutConstructor('Packagist\Api\Client');
        $context      = $this->getMockWithoutConstructor('CrudGenerator\Context\CliContext');

        $packagistApi->expects($this->once())
        ->method('search')
        ->willReturn(array(new \Packagist\Api\Result\Result()));

        $context->expects($this->once())
        ->method('ask')
        ->willReturn(1);

        $context->expects($this->once())
        ->method('askCollection')
        ->willReturn(1);

        $sUT = new GeneratorSearch($packagistApi, $context);

        $this->assertEquals(1, $sUT->ask());
    }

    /**
     * @param  string                                   $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockWithoutConstructor($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}
