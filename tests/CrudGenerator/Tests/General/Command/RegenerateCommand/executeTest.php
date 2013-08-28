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
        $history = new History();
        $history->setName('messages')
        ->setDataObject(new Architect());


        $historyStub = $this->getMockBuilder('\CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue(array(0 => $history)));

        $CodeGeneratorFactoryStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $CodeGeneratorFactoryStub->expects($this->any())
        ->method('create')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $application = new App();
        $application->add(new RegenerateCommand(null, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:regenerate');

        chdir(__DIR__ . '/../../../ZF2/');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('select', 'askConfirmation'));
        $dialog->expects($this->once())
               ->method('select')
               ->will($this->returnValue(0));

        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(false));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $command->getName()));
    }

    public function testEmptyHistory()
    {
        $historyStub = $this->getMockBuilder('\CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue(array()));

        $CodeGeneratorFactoryStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();

        $application = new App();
        $application->add(new RegenerateCommand(null, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:regenerate');

        chdir(__DIR__ . '/../../../ZF2/');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('select', 'askConfirmation'));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $command->getName()));
    }

    public function testDoiTrue()
    {
        $history = new History();
        $history->setName('messages')
                ->setDataObject(new Architect());


        $historyStub = $this->getMockBuilder('\CrudGenerator\History\HistoryManager')
                            ->disableOriginalConstructor()
                            ->getMock();
        $historyStub->expects($this->once())
                    ->method('findAll')
                    ->will($this->returnValue(array(0 => $history)));

        $CodeGeneratorFactoryStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
                                         ->disableOriginalConstructor()
                                         ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
                                       ->disableOriginalConstructor()
                                       ->getMock();
        $CodeGeneratorFactoryStub->expects($this->any())
                                 ->method('create')
                                 ->will($this->returnValue($ArchitectGeneratorStub));

        chdir(__DIR__ . '/../../../ZF2/');

        $application = new App();
        $application->add(new RegenerateCommand(null, $historyStub, $CodeGeneratorFactoryStub));

        $command = $application->find('CodeGenerator:regenerate');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('select', 'askConfirmation'));
        $dialog->expects($this->once())
               ->method('select')
               ->will($this->returnValue(0));

        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(true));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);

        $commandTester->execute(array('command' => $command->getName()));
    }
}
