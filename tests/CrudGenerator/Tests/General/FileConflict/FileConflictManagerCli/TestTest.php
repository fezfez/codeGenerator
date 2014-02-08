<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManager;

use CrudGenerator\FileConflict\FileConflictManagerCli;

class TestTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->exactly(2))
        ->method('isFile')
        ->will($this->returnValue(true));

        $fileManager->expects($this->exactly(2))
        ->method('fileGetContent')
        ->will($this->returnValue('test'));

        $diffPHP = $this->getMockBuilder('SebastianBergmann\Diff\Differ')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileConflictManagerCli($ConsoleOutputStub, $dialog, $fileManager, $diffPHP);

        $this->assertEquals(
            true,
            $sUT->test('test', '0')
        );

        $this->assertEquals(
            false,
            $sUT->test('test', 'test')
        );
    }
}