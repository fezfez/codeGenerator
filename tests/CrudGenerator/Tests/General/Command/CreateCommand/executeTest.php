<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Tests\General\Command\CommandTestCase;

use CrudGenerator\Command\CreateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Command\Questions\MetaDataSourcesQuestion;
use CrudGenerator\Command\Questions\DirectoryQuestion;
use CrudGenerator\Command\Questions\MetaDataQuestion;
use CrudGenerator\Command\Questions\GeneratorQuestion;
use CrudGenerator\MetaData\MetaDataSource;

use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class executeTest extends \PHPUnit_Framework_TestCase
{

    public function testFadzadazdzail()
    {
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub->expects($this->once())
        ->method('getDTO')
        ->will($this->returnValue('\CrudGenerator\Generators\ArchitectGenerator\Architect'));

        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataSourcesQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\MetaDataSourcesQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataSourcesQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue(new MetaDataSource()));
        $DirectoryQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $metadata = new \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $MetaDataQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($metadata));

        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(false));

        $commandTmp = new CreateCommand(
            $dialog,
            $ConsoleOutputStub,
            $historyStub,
            $MetaDataSourcesQuestionStub,
            $DirectoryQuestionStub,
            $MetaDataQuestionStub,
            $GeneratorQuestionStub
        );
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create');

        // We override the standard helper with our mock
        $sUT->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($sUT);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $sUT->getName()));
    }

    public function testYesdzadz()
    {
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $ArchitectGeneratorStub->expects($this->once())
        ->method('generate')
        ->will($this->returnValue(new \CrudGenerator\Generators\ArchitectGenerator\Architect()));
        $ArchitectGeneratorStub->expects($this->once())
        ->method('getDTO')
        ->will($this->returnValue('\CrudGenerator\Generators\ArchitectGenerator\Architect'));

        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub
        ->expects($this->once())
        ->method('create')
        ->will($this->returnValue(''));

        $MetaDataSourcesQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\MetaDataSourcesQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataSourcesQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue(new MetaDataSource()));
        $DirectoryQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $metadata = new \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $MetaDataQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($metadata));

        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(true));

        $commandTmp = new CreateCommand(
            $dialog,
            $ConsoleOutputStub,
            $historyStub,
            $MetaDataSourcesQuestionStub,
            $DirectoryQuestionStub,
            $MetaDataQuestionStub,
            $GeneratorQuestionStub
        );
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create');

        // We override the standard helper with our mock
        $sUT->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($sUT);

        $commandTester->execute(array('command' => $sUT->getName()));
    }
}
