<?php
namespace CrudGenerator\Tests\General\Command\CreateCommandFactory;

use CrudGenerator\Command\CreateCommandFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $application =  $this->getMockBuilder('Symfony\Component\Console\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\Command\CreateCommand',
            CreateCommandFactory::getInstance($application)
        );
    }
}
