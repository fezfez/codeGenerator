<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Tests\General\Command\CommandTestCase;

use CrudGenerator\Command\CreateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

class executeTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzadza()
    {
        $application = new App();
        $application->add(new CreateCommand());

        $command = $application->find('CodeGenerator:create');

        /*$tmpfname = tempnam("/tmp", "FOO");

        $handle = fopen($tmpfname, "w");
        define('STDIN', $handle);*/
        chdir(__DIR__ . '/../../../ZF2/MetaData/');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askAndValidate', 'askConfirmation', 'select'));
        $dialog->expects($this->at(0))
               ->method('select')
               ->will($this->returnValue('CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAO'));
        $dialog->expects($this->at(1))
               ->method('select')
               ->will($this->returnValue('TestZf2\Entities\NewsEntity'));
        $dialog->expects($this->at(2))
               ->method('select')
               ->will($this->returnValue('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'));
        $dialog->expects($this->at(3))
               ->method('select')
               ->will($this->returnValue('CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'));
        $dialog->expects($this->at(0))
               ->method('askConfirmation')
               ->will($this->returnValue('n'));

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($command);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $command->getName()));
    }
}
