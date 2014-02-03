<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Cli\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Cli\DirectoryQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

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
        ->with(
            $this->equalTo($ConsoleOutputStub),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will($this->returnValue(4));
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


        $fileManagerStub =  new \CrudGenerator\Utils\FileManager();

        $sUT = new DirectoryQuestion($fileManagerStub, $ConsoleOutputStub, $dialog);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $this->assertEquals('./', $sUT->ask($generatorDTO, array('attribute' => 'ModelDirectory'))->getDTO()->getModelDirectory());
    }

    public function testOkWithCreateFile()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManagerStub =  $this->getMockBuilder('\CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog->expects($this->exactly(2))
        ->method('select')
        ->with(
            $this->equalTo($ConsoleOutputStub),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    2
                ),
                $this->returnValue(
                    1
                )
            )
        );

        $dialog->expects($this->exactly(2))
        ->method('ask')
        ->with(
            $this->equalTo($ConsoleOutputStub),
            $this->isType('string')
        )
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    'myFalseDir'
                ),
                $this->returnValue(
                    'MyTrueDir'
                )
            )
        );

        $fileManagerStub->expects($this->exactly(2))
        ->method('ifDirDoesNotExistCreate')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    false
                ),
                $this->returnValue(
                    true
                )
            )
        );

        $fileManagerStub->expects($this->exactly(2))
        ->method('glob')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    array(
                        'mmydir',
                        'myFile',
                        'myFile2'
                    )
                ),
                $this->returnValue(
                    array()
                )
            )
        );

        $sUT = new DirectoryQuestion($fileManagerStub, $ConsoleOutputStub, $dialog);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $this->assertEquals('./MyTrueDir/',  $sUT->ask($generatorDTO, array('attribute' => 'ModelDirectory'))->getDTO()->getModelDirectory());
    }
}