<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\FileManager;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class directoryQuestionTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate'), array(), '', false, false, false);

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');

        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, $stubFileManager);

        $generiqueQuestion->directoryQuestion(new Architect());
    }

    /*public function testFail()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $config = include __DIR__ . '/../../../ZF2/config/application.config.php';
        $config['crudGenerator']['path'] = array(
            __DIR__,
            __DIR__ . '/FZEAFAZ/'
        );
        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue($config));

        $suT = new GeneratorFinder($stubFileManager);

        $this->setExpectedException('RuntimeException');


        $suT->getAllClasses();
    }*/

   /* public function testFailOnGetConfig()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->onConsecutiveCalls(true, false));

        $suT = new GeneratorFinder($stubFileManager);

        $this->setExpectedException('RuntimeException');

        $suT->getAllClasses();
    }*/
}
