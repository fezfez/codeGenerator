<?php
namespace CrudGenerator\Tests\General\Command\Questions\GeneratorQuestion;


use CrudGenerator\Command\Questions\GeneratorQuestion;

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

        $dialog->expects($this->once())
        ->method('select')
        ->will($this->returnValue(0));

        $generatorCollection = array();
        $generatorStub =  $this->getMockBuilder('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $generatorCollection[] = 'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator';


        $codeGeneratorStub = $this->getMockBuilder('CrudGenerator\Generators\CodeGeneratorFactory', array('create'))
        ->disableOriginalConstructor()
        ->getMock();
        $codeGeneratorStub->expects($this->once())
        ->method('create')
        ->will($this->returnValue($generatorStub));

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub->expects($this->once())
        ->method('getAllClasses')
        ->will($this->returnValue($generatorCollection));


        $sUT = new GeneratorQuestion($sourceFinderStub, $codeGeneratorStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($generatorStub, $sUT->ask());
    }
}