<?php
namespace CrudGenerator\Tests\General\Command\Questions\GeneratorQuestion;


use CrudGenerator\Command\Questions\GeneratorQuestion;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $consoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $generatorStub =  $this->getMockBuilder('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $codeGeneratorStub = $this->getMockBuilder('CrudGenerator\Generators\CodeGeneratorFactory', array('create'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $consoleOutputStub->expects($this->any())
                          ->method('writeln')
                          ->will($this->returnValue(''));

        $dialog->expects($this->once())
               ->method('select')
               ->will($this->returnValue(0));

        $codeGeneratorStub->expects($this->once())
                          ->method('create')
                          ->will($this->returnValue($generatorStub));

        $sourceFinderStub->expects($this->once())
                         ->method('getAllClasses')
                         ->will(
                            $this->returnValue(
                                array(
                                    'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
                                )
                            )
                        );


        $sUT = new GeneratorQuestion($sourceFinderStub, $codeGeneratorStub, $consoleOutputStub, $dialog);
        $this->assertEquals($generatorStub, $sUT->ask());
    }

    public function testWithInvalidDefault()
    {
        $consoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $generatorStub =  $this->getMockBuilder('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $codeGeneratorStub = $this->getMockBuilder('CrudGenerator\Generators\CodeGeneratorFactory', array('create'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
        ->method('getAllClasses')
        ->will(
            $this->returnValue(
                array(
                    'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
                )
            )
        );


        $sUT = new GeneratorQuestion($sourceFinderStub, $codeGeneratorStub, $consoleOutputStub, $dialog);

        $this->setExpectedException('Exception');

        $sUT->ask('CrudGenerator\Generators\ArchitectGenerator\ArchitGenerator');
    }

    public function testWithDefault()
    {
        $consoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $generatorStub =  $this->getMockBuilder('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $codeGeneratorStub = $this->getMockBuilder('CrudGenerator\Generators\CodeGeneratorFactory', array('create'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
        ->method('getAllClasses')
        ->will(
            $this->returnValue(
                array(
                    'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
                )
            )
        );

        $codeGeneratorStub->expects($this->once())
        ->method('create')
        ->will($this->returnValue($generatorStub));


        $sUT = new GeneratorQuestion($sourceFinderStub, $codeGeneratorStub, $consoleOutputStub, $dialog);

        $this->assertEquals($generatorStub, $sUT->ask('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'));
    }
}