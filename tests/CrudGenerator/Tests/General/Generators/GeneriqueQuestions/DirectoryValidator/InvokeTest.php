<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneriqueQuestion\DirectoryValidator;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\DataObject;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class InvokeTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate'), array(), '', false, false, false);

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('isDir')
                        ->will($this->returnValue(true));

        $dto = new Architect();

        $generiqueQuestion = new DirectoryValidator($dto, $stubFileManager);

        $this->assertEquals('test', $generiqueQuestion('test'));
    }

    public function testFail()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate'), array(), '', false, false, false);

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('isDir')
                        ->will($this->returnValue(false));

        $dto = new Architect();

        $generiqueQuestion = new DirectoryValidator($dto, $stubFileManager);

        $this->setExpectedException('InvalidArgumentException');
        $generiqueQuestion('test');
    }
}
