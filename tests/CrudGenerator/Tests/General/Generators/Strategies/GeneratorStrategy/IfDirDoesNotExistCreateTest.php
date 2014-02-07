<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategy;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;

class IfDirDoesNotExistCreateTest extends \PHPUnit_Framework_TestCase
{
    public function testWithGenerate()
    {
        $view = $this->getMockBuilder('CrudGenerator\View\View')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();


        $sUT = new GeneratorStrategy($view, $fileManager);

        $sUT->ifDirDoesNotExistCreate('MyDir');
    }

    public function testNone()
    {
        $view = $this->getMockBuilder('CrudGenerator\View\View')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new GeneratorStrategy($view, $fileManager);

        $sUT->ifDirDoesNotExistCreate('MyDir');
    }
}