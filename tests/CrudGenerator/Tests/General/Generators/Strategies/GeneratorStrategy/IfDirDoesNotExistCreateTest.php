<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategy;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;

class IfDirDoesNotExistCreateTest extends \PHPUnit_Framework_TestCase
{
    public function testWithGenerate()
    {
        $output =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $view = $this->getMockBuilder('CrudGenerator\View\View')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();
        $fileConflitManager = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();

        $output->expects($this->once())
        ->method('writeln');
        $fileManager->expects($this->once())
        ->method('ifDirDoesNotExistCreate')
        ->will($this->returnValue(true));

        $sUT = new GeneratorStrategy($view, $output, $fileManager, $fileConflitManager);

        $sUT->ifDirDoesNotExistCreate('MyDir');
    }

    public function testNone()
    {
        $output =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $view = $this->getMockBuilder('CrudGenerator\View\View')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();
        $fileConflitManager = $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();

        $output->expects($this->never())
        ->method('writeln');
        $fileManager->expects($this->once())
        ->method('ifDirDoesNotExistCreate')
        ->will($this->returnValue(false));

        $sUT = new GeneratorStrategy($view, $output, $fileManager, $fileConflitManager);

        $sUT->ifDirDoesNotExistCreate('MyDir');
    }
}