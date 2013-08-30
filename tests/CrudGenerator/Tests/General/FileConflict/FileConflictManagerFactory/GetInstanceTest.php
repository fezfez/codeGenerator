<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerFactory;

use CrudGenerator\FileConflict\FileConflictManagerFactory;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $this->assertInstanceOf(
            'CrudGenerator\FileConflict\FileConflictManager',
            FileConflictManagerFactory::getInstance($ConsoleOutputStub, $dialog)
        );
    }
}