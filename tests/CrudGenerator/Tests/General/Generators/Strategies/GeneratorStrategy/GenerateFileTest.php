<?php
namespace CrudGenerator\Tests\General\Generators\Strategies\GeneratorStrategy;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class GenerateFileTest extends \PHPUnit_Framework_TestCase
{
    public function testWithGenerate()
    {
        $view = $this->getMockBuilder('CrudGenerator\View\View')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
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

        $sUT = new GeneratorStrategy($view, $fileManager);

        $sUT->generateFile(array('dataObject' => $dataObject), $skeletonDir, $pathTemplate, $pathTo);
    }
}