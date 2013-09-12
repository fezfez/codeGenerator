<?php
namespace CrudGenerator\Tests\General\Command\RegenerateCommand;

use CrudGenerator\Generators\ArchitectGenerator\Architect;

use CrudGenerator\History\History;

use CrudGenerator\Command\RegenerateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

class executeTest extends \PHPUnit_Framework_TestCase
{
   public function testDoiNo()
    {
        $history = new Architect();


        $historyStub = $this->getMockBuilder('CrudGenerator\Command\Questions\HistoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($history));

        $CodeGeneratorFactoryStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $CodeGeneratorFactoryStub->expects($this->any())
        ->method('create')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));

        $dialog->expects($this->once())
        ->method('askConfirmation')
        ->will($this->returnValue(false));

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();


        $application = new App();
        $application->add(new RegenerateCommand($dialog, $ConsoleOutputStub, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:regenerate');


        $commandTester = new CommandTester($command);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $command->getName()));
    }

    public function testDoiTrue()
    {
        $history = new Architect();


        $historyStub = $this->getMockBuilder('CrudGenerator\Command\Questions\HistoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($history));

        $CodeGeneratorFactoryStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $CodeGeneratorFactoryStub->expects($this->any())
        ->method('create')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $dialog->expects($this->once())
        ->method('askConfirmation')
        ->will($this->returnValue(true));

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();


        $application = new App();
        $application->add(new RegenerateCommand($dialog, $ConsoleOutputStub, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:regenerate');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }
}
