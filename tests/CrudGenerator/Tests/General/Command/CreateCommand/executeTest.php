<?php
namespace CrudGenerator\Tests\General\Command\CreateCommand;

use CrudGenerator\Command\CreateCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\CommandTester;
use CrudGenerator\History\HistoryManager;
use CrudGenerator\Generators\Questions\Cli\MetaDataSourcesQuestion;
use CrudGenerator\Generators\Questions\Cli\DirectoryQuestion;
use CrudGenerator\Generators\Questions\Cli\MetaDataQuestion;
use CrudGenerator\Generators\Questions\Cli\GeneratorQuestion;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Generators\GeneratorDataObject;

class executeTest extends \PHPUnit_Framework_TestCase
{

    public function testFailOKOKOK()
    {
        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataSourcesQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataSourcesQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue(new MetaDataSource()));
        $DirectoryQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $metadata = new \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $MetaDataQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($metadata));

        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue("ArchitectGenerator"));

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(false));

        $dialog->expects($this->any())
        ->method('ask')
        ->will($this->returnValue(''));

        $parserStub = $this->getMockBuilder('CrudGenerator\Generators\Parser\GeneratorParser')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $parserStub->expects($this->any())
        ->method('init')
        ->will($this->returnValue($generatorDTO));

        $generatorStub = $this->getMockBuilder('CrudGenerator\Generators\GeneratorCli')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $commandTmp = new CreateCommand(
            $parserStub,
        	$generatorStub,
            $historyStub,
            $MetaDataSourcesQuestionStub,
            $MetaDataQuestionStub,
            $GeneratorQuestionStub,
        	$context
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

    public function testYes()
    {
        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub
        ->expects($this->once())
        ->method('create')
        ->will($this->returnValue(''));

        $MetaDataSourcesQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataSourcesQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue(new MetaDataSource()));
        $DirectoryQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $metadata = new \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $MetaDataQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\MetaDataQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $MetaDataQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($metadata));

        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue('ArchitectGenerator'));

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(true));

        $dialog->expects($this->any())
        ->method('ask')
        ->will($this->returnValue(''));

        $parserStub = $this->getMockBuilder('CrudGenerator\Generators\Parser\GeneratorParser')
        ->disableOriginalConstructor()
        ->getMock();

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $parserStub->expects($this->any())
        ->method('init')
        ->will($this->returnValue($generatorDTO));

        $generatorStub = $this->getMockBuilder('CrudGenerator\Generators\GeneratorCli')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $commandTmp = new CreateCommand(
            $parserStub,
        	$generatorStub,
            $historyStub,
            $MetaDataSourcesQuestionStub,
            $MetaDataQuestionStub,
            $GeneratorQuestionStub,
        	$context
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
