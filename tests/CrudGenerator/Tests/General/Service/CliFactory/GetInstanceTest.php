<?php
namespace CrudGenerator\Tests\General\Service\CliFactory;

use CrudGenerator\Service\CliFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $inputStub = $this->getMockBuilder('Symfony\Component\Console\Input\ArgvInput')
        ->disableOriginalConstructor()
        ->getMock();
        $outputStub = $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'Symfony\Component\Console\Application',
            CliFactory::getInstance($inputStub, $outputStub)
        );
    }
}
