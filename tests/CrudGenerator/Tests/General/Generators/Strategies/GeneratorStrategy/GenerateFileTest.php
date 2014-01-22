<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategy;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class GenerateFileTest extends \PHPUnit_Framework_TestCase
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

        $templateResult = 'MyResults';
        $dataObject     = new Architect();
        $skeletonDir    = 'MySkeletonDir';
        $pathTemplate   = 'myPathTemplate';
        $pathTo         = 'MyPathTo';

        $view->expects($this->once())
        ->method('render')
        ->with(
            $this->equalTo($skeletonDir),
            $this->equalTo($pathTemplate),
            $this->equalTo(
                array(
                    'dataObject' => $dataObject,
                )
            )
        )
        ->will($this->returnValue($templateResult));

        $fileConflitManager->expects($this->once())
        ->method('test')
        ->with(
            $this->equalTo($pathTo),
            $this->equalTo($templateResult)
        )
        ->will($this->returnValue(false));

        $fileManager->expects($this->once())
        ->method('filePutsContent')
        ->with(
            $this->equalTo($pathTo),
            $this->equalTo($templateResult)
        )
        ->will($this->returnValue(true));

        $sUT = new GeneratorStrategy($view, $output, $fileManager, $fileConflitManager);

        $sUT->generateFile(array('dataObject' => $dataObject), $skeletonDir, $pathTemplate, $pathTo);
    }

    public function testWithConflict()
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

        $templateResult = 'MyResults';
        $dataObject     = new Architect();
        $skeletonDir    = 'MySkeletonDir';
        $pathTemplate   = 'myPathTemplate';
        $pathTo         = 'MyPathTo';

        $view->expects($this->once())
        ->method('render')
        ->with(
            $this->equalTo($skeletonDir),
            $this->equalTo($pathTemplate),
            $this->equalTo(
                array(
                    'dataObject' => $dataObject,
                )
            )
        )
        ->will($this->returnValue($templateResult));

        $fileConflitManager->expects($this->once())
        ->method('test')
        ->with(
            $this->equalTo($pathTo),
            $this->equalTo($templateResult)
        )
        ->will($this->returnValue(true));

        $fileConflitManager->expects($this->once())
        ->method('handle')
        ->with(
            $this->equalTo($pathTo),
            $this->equalTo($templateResult)
        )
        ->will($this->returnValue(true));

        $sUT = new GeneratorStrategy($view, $output, $fileManager, $fileConflitManager);

        $sUT->generateFile(array('dataObject' => $dataObject), $skeletonDir, $pathTemplate, $pathTo);
    }
}