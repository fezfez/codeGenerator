<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerWeb;

use CrudGenerator\FileConflict\FileConflictManagerWeb;

class HandleTest extends \PHPUnit_Framework_TestCase
{
   public function testHandle()
   {
        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue('test'));

        $diffPHP = $this->getMockBuilder('SebastianBergmann\Diff\Differ')
        ->disableOriginalConstructor()
        ->getMock();

        $diffPHP->expects($this->once())
        ->method('diff')
        ->will($this->returnValue('test'));

        $sUT = new FileConflictManagerWeb($fileManager, $diffPHP);

        $this->assertEquals(
        	'test',
        	$sUT->handle('test', '0')
        );
    }
}