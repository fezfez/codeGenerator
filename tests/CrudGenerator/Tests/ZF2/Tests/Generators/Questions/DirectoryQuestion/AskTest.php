<?php
namespace CrudGenerator\Tests\ZF2\Tests\Generators\Questions\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Cli\DirectoryQuestion;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOkdzad()
    {
        chdir(__DIR__ . '/../../../../');

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
        ->with(
            $this->equalTo($ConsoleOutputStub),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will($this->returnValue(3));
        // then choice back
        $dialog->expects($this->at(1))
        ->method('select')
        ->with(
            $this->equalTo($ConsoleOutputStub),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will($this->returnValue(0));
        // then choice actual directory
        $dialog->expects($this->at(2))
        ->method('select')
        ->with(
            $this->equalTo($ConsoleOutputStub),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will($this->returnValue(1));


        $fileManagerStub = new FileManager();

        $sUT = new DirectoryQuestion($fileManagerStub, $ConsoleOutputStub, $dialog);


        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $this->assertEquals('./module/', $sUT->ask($generatorDTO)->getDTO()->getModule());
    }
}