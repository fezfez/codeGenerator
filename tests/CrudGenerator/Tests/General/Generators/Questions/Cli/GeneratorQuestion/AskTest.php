<?php
namespace CrudGenerator\Tests\General\Command\Questions\Cli\GeneratorQuestion;


use CrudGenerator\Generators\Questions\Cli\GeneratorQuestion;

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
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $consoleOutputStub->expects($this->any())
                          ->method('writeln')
                          ->will($this->returnValue(''));

        $dialog->expects($this->once())
               ->method('select')
               ->will($this->returnValue(0));

        $sourceFinderStub->expects($this->once())
                         ->method('getAllClasses')
                         ->will(
                            $this->returnValue(
                                array(
                                    'path/ArchitectGenerator.generator.yaml' => 'ArchitectGenerator'
                                )
                            )
                        );


        $sUT = new GeneratorQuestion($sourceFinderStub, $consoleOutputStub, $dialog);
        $this->assertEquals('ArchitectGenerator', $sUT->ask());
    }

    public function testWithInvalidDefault()
    {
        $consoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
        ->method('getAllClasses')
        ->will(
            $this->returnValue(
                array(
                    'path/ArchitectGenerator.generator.yaml' => 'ArchitectGenerator'
                )
            )
        );


        $sUT = new GeneratorQuestion($sourceFinderStub, $consoleOutputStub, $dialog);

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
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
        ->method('getAllClasses')
        ->will(
            $this->returnValue(
                array(
                    'path/ArchitectGenerator.generator.yaml' => 'ArchitectGenerator'
                )
            )
        );


        $sUT = new GeneratorQuestion($sourceFinderStub, $consoleOutputStub, $dialog);

        $this->assertEquals('ArchitectGenerator', $sUT->ask('ArchitectGenerator'));
    }
}