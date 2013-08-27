<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Tests\General\Command\CommandTestCase;

use CrudGenerator\Command\CreateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;


class executeTest extends \PHPUnit_Framework_TestCase
{

    public function testFaidzadzal()
    {
        //chdir(__DIR__ . '/../../../ZF2/MetaData/');

        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $CodeGeneratorFactoryStub = $this->getMockBuilder('CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $CodeGeneratorFactoryStub->expects($this->any())
        ->method('create')
        ->will($this->returnValue($ArchitectGeneratorStub));
        $configReaderStub = $this->getMockBuilder('\CrudGenerator\MetaData\Config\MetaDataConfigReader')
        ->disableOriginalConstructor()
        ->getMock();
        $configReaderStub->expects($this->once())
        ->method('config')
        ->will($this->returnValue(include __DIR__ . '/../../../PDO/MetaData/PDO/PgSql/config.php'));


        //chdir();

        $commandTmp = new CreateCommand(null, $historyStub, $CodeGeneratorFactoryStub, $configReaderStub);
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation', 'select'));
        $dialog->expects($this->at(0))
               ->method('select')
               ->will($this->returnValue('0'));
        $dialog->expects($this->at(1))
               ->method('select')
               ->will($this->returnValue('0'));
        $dialog->expects($this->at(2))
               ->method('select')
               ->will($this->returnValue('0'));
        $dialog->expects($this->at(3))
               ->method('select')
               ->will($this->returnValue('0'));
        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(false));

        // We override the standard helper with our mock
        $sUT->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($sUT);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $sUT->getName()));
    }

    public function testYes()
    {
        chdir(__DIR__ . '/../../../ZF2/MetaData/');

        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $CodeGeneratorFactoryStub = $this->getMockBuilder('CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub->expects($this->once())
        ->method('generate')
        ->will($this->returnValue(new \CrudGenerator\Generators\ArchitectGenerator\Architect()));
        $CodeGeneratorFactoryStub->expects($this->any())
        ->method('create')
        ->will($this->returnValue($ArchitectGeneratorStub));


        $commandTmp = new CreateCommand(null, $historyStub, $CodeGeneratorFactoryStub);
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create');

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation', 'select'));
        $dialog->expects($this->at(0))
        ->method('select')
        ->will($this->returnValue('0'));
        $dialog->expects($this->at(1))
        ->method('select')
        ->will($this->returnValue('0'));
        $dialog->expects($this->at(2))
        ->method('select')
        ->will($this->returnValue('0'));
        $dialog->expects($this->at(3))
        ->method('select')
        ->will($this->returnValue('0'));
        $dialog->expects($this->once())
        ->method('askConfirmation')
        ->will($this->returnValue(true));

        // We override the standard helper with our mock
        $sUT->getHelperSet()->set($dialog, 'dialog');


        $commandTester = new CommandTester($sUT);

        $commandTester->execute(array('command' => $sUT->getName()));
    }
}
