<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerWeb;

use CrudGenerator\FileConflict\FileConflictManagerWeb;

class TestTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
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

        $sUT = new FileConflictManagerWeb($fileManager, $diffPHP);

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