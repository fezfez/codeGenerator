<?php
namespace CrudGenerator\Tests\General\Command\CreateByYamlCommand;

use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\Command\CreateByYamlCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\CommandTester;

class executeREWRITE extends \PHPUnit_Framework_TestCase
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
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
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
        $application->add(new CreateByYamlCommand($dialog, $ConsoleOutputStub, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:create-by-yaml');


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
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub->expects($this->once())
        ->method('generate');
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
        $application->add(new CreateByYamlCommand($dialog, $ConsoleOutputStub, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:create-by-yaml');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }
}
