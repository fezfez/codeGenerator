<?php
namespace CrudGenerator\Tests\General\FileManagerStub;

use CrudGenerator\Utils\FileManagerStub;

class FilePutsContentTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $stubDialog = $this->getMockBuilder('\Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubDialog->expects($this->once())
        ->method('askConfirmation')
        ->will($this->returnValue(false));

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileManagerStub($stubDialog, $stubOutput);

        $sUT->filePutsContent(__FILE__, 'toto');
    }

    public function testRegenerate()
    {
        $stubDialog = $this->getMockBuilder('\Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $stubDialog->expects($this->once())
        ->method('askConfirmation')
        ->will($this->returnValue(true));

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileManagerStub($stubDialog, $stubOutput);

        $this->setExpectedException('Exception');
        $sUT->filePutsContent(__FILE__, 'toto');
    }
}
