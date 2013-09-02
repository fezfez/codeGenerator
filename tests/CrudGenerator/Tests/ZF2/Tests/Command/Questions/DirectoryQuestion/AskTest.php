<?php
namespace CrudGenerator\Tests\ZF2\Tests\Command\Questions\DirectoryQuestion;


use CrudGenerator\Command\Questions\DirectoryQuestion;
use CrudGenerator\Utils\FileManager;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        chdir(__DIR__);
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        // First choice bin
        $dialog->expects($this->at(0))
        ->method('select')
        ->will($this->returnValue(3));
        // then choice back
        $dialog->expects($this->at(1))
        ->method('select')
        ->will($this->returnValue(0));
        // then choice actual directory
        $dialog->expects($this->at(2))
        ->method('select')
        ->will($this->returnValue(1));


        $fileManagerStub = new FileManager();

        $sUT = new DirectoryQuestion($fileManagerStub, $ConsoleOutputStub, $dialog);

        $this->assertEquals('', $sUT->ask());
    }
}