<?php
namespace CrudGenerator\Tests\General\Command\Questions\DirectoryQuestion;


use CrudGenerator\Command\Questions\DirectoryQuestion;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
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


        $fileManagerStub =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManagerStub->expects($this->exactly(3))
        ->method('glob')
        ->will($this->returnValue(array('bin')));

        $sUT = new DirectoryQuestion($fileManagerStub, $ConsoleOutputStub, $dialog);

        $this->assertEquals('', $sUT->ask());
    }
}