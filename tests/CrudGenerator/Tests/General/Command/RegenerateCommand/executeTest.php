<?php
namespace CrudGenerator\Tests\General\Command\RegenerateCommand;

use CrudGenerator\Command\RegenerateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

class executeTest extends \PHPUnit_Framework_TestCase
{
    public function testDoiNo()
    {
        $application = new App();
        $application->add(new RegenerateCommand());

        $command = $application->find('CodeGenerator:regenerate');

        /*$tmpfname = tempnam("/tmp", "FOO");

        $handle = fopen($tmpfname, "w");
        define('STDIN', $handle);*/
        chdir(__DIR__ . '/../../../ZF2/MetaData/');

        $architect = new \CrudGenerator\Generators\ArchitectGenerator\Architect();
        $architect->setEntity('toto')
                  ->setModule('tutu')
                  ->setGenerator('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator');

        $history = new \CrudGenerator\History\History();
        $history->setName('message')
                ->setDataObject($architect);

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate', 'askConfirmation'));
        $dialog->expects($this->at(0))
               ->method('askAndValidate')
               ->will($this->returnValue($history));

        $dialog->expects($this->at(0))
               ->method('askConfirmation')
               ->will($this->returnValue('n'));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $command->getName()));
    }

    public function testDoiTrue()
    {
        $application = new App();
        $application->add(new RegenerateCommand());

        $command = $application->find('CodeGenerator:regenerate');

        /*$tmpfname = tempnam("/tmp", "FOO");

        $handle = fopen($tmpfname, "w");
        define('STDIN', $handle);*/
        chdir(__DIR__ . '/../../../ZF2/MetaData/');

        $architect = new \CrudGenerator\Generators\ArchitectGenerator\Architect();
        $architect->setEntity('toto')
        ->setModule('tutu')
        ->setGenerator('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator');

        $history = new \CrudGenerator\History\History();
        $history->setName('message')
        ->setDataObject($architect);

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate', 'askConfirmation'));
        $dialog->expects($this->at(0))
        ->method('askAndValidate')
        ->will($this->returnValue($history));

        $dialog->expects($this->any())
        ->method('askConfirmation')
        ->will($this->returnValue(true));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $command->getName()));
    }
}
