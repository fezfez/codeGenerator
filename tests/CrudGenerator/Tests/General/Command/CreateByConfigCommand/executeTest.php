<?php
namespace CrudGenerator\Tests\General\Command\CreateByConfigCommand;

use CrudGenerator\Tests\General\Command\CommandTestCase;

use CrudGenerator\Command\CreateByConfigCommand;
use Symfony\Component\Console\Application as App;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Command\Questions\MetaDataSourcesQuestion;
use CrudGenerator\Command\Questions\DirectoryQuestion;
use CrudGenerator\Command\Questions\MetaDataQuestion;
use CrudGenerator\Command\Questions\GeneratorQuestion;

use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\ConfigManager\ConfigGenerator\DataObject\ConfigDataObject;
use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject;
use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\MetadataDataObjectConfig;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\Tests\General\History\HistoryManager\YampToDtoTest;

class executeTest extends \PHPUnit_Framework_TestCase
{

    public function testFail()
    {
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $DirectoryQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $configGeneratorFactory = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigGenerator\ManagerFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $configGenerator = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $configMetadataFactory = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigMetadata\ManagerFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $configMetadata = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigMetadata\Manager\YamlConfigMetadata')
        ->disableOriginalConstructor()
        ->getMock();


        $ArchitectGeneratorStub->expects($this->once())
        ->method('getDTO')
        ->will($this->returnValue('\CrudGenerator\Generators\ArchitectGenerator\Architect'));

        $metadata = new \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $GeneratorQuestionStub
        ->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(false));

        $configGeneratorDTO = new ConfigDataObject(
            'Corp',
            'data',
            'data',
            'data'
        );

        $configGenerator->expects($this->once())
        ->method('getConfig')
        ->will($this->returnValue($configGeneratorDTO));

        $configGeneratorFactory->expects($this->once())
        ->method('create')
        ->will($this->returnValue($configGenerator));

        $metadata = new MetadataDataObjectConfig(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $yamlConfigMetadataDTO = new YamlConfigDataObject();
        $yamlConfigMetadataDTO->setGenerators(array('NonRealGenerator'));
        $yamlConfigMetadataDTO->setMetadata($metadata);

        $configMetadata->expects($this->once())
        ->method('getMetadatas')
        ->will($this->returnValue($yamlConfigMetadataDTO));

        $configMetadata->expects($this->once())
        ->method('writeAbstractOptions')
        ->will($this->returnValue(new Architect()));

        $configMetadataFactory->expects($this->once())
        ->method('create')
        ->will($this->returnValue($configMetadata));

        $commandTmp = new CreateByConfigCommand(
            $dialog,
            $ConsoleOutputStub,
            $historyStub,
            $DirectoryQuestionStub,
            $GeneratorQuestionStub,
            $configGeneratorFactory,
            $configMetadataFactory
        );
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create-by-config');

        // We override the standard helper with our mock
        $sUT->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($sUT);
        $this->setExpectedException('RuntimeException');
        $commandTester->execute(array('command' => $sUT->getName()));
    }

    public function testYes()
    {
        $ArchitectGeneratorStub = $this->getMockBuilder('\CrudGenerator\Generators\CodeGeneratorFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $historyStub = $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $DirectoryQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $GeneratorQuestionStub = $this->getMockBuilder('CrudGenerator\Command\Questions\GeneratorQuestion')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('askConfirmation'));
        $configGeneratorFactory = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigGenerator\ManagerFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $configGenerator = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator')
        ->disableOriginalConstructor()
        ->getMock();
        $configMetadataFactory = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigMetadata\ManagerFactory')
        ->disableOriginalConstructor()
        ->getMock();
        $configMetadata = $this->getMockBuilder('CrudGenerator\ConfigManager\ConfigMetadata\Manager\YamlConfigMetadata')
        ->disableOriginalConstructor()
        ->getMock();

        $dto = new \CrudGenerator\Generators\ArchitectGenerator\Architect();
        $ArchitectGeneratorStub->expects($this->once())
        ->method('getDTO')
        ->will($this->returnValue('\CrudGenerator\Generators\ArchitectGenerator\Architect'));
        $ArchitectGeneratorStub->expects($this->once())
        ->method('generate')
        ->will($this->returnValue($dto));

        /*$historyStub->expects($this->once())
        ->method('CreateByConfig')
        ->will($this->returnValue(''));*/

        $metadata = new \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $GeneratorQuestionStub->expects($this->once())
        ->method('ask')
        ->will($this->returnValue($ArchitectGeneratorStub));

        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog->expects($this->once())
               ->method('askConfirmation')
               ->will($this->returnValue(true));


        $configGeneratorDTO = new ConfigDataObject(
            'Corp',
            'data',
            'data',
            'data'
        );

        $configGenerator->expects($this->once())
        ->method('getConfig')
        ->will($this->returnValue($configGeneratorDTO));

        $configGeneratorFactory->expects($this->once())
        ->method('create')
        ->will($this->returnValue($configGenerator));

        $metadata = new MetadataDataObjectConfig(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $yamlConfigMetadataDTO = new YamlConfigDataObject();
        $yamlConfigMetadataDTO->setGenerators(array('NonRealGenerator'));
        $yamlConfigMetadataDTO->setMetadata($metadata);

        $configMetadata->expects($this->once())
        ->method('getMetadatas')
        ->will($this->returnValue($yamlConfigMetadataDTO));

        $configMetadata->expects($this->once())
        ->method('writeAbstractOptions')
        ->will($this->returnValue(new Architect()));

        $configMetadataFactory->expects($this->once())
        ->method('create')
        ->will($this->returnValue($configMetadata));

        $commandTmp = new CreateByConfigCommand(
            $dialog,
            $ConsoleOutputStub,
            $historyStub,
            $DirectoryQuestionStub,
            $GeneratorQuestionStub,
            $configGeneratorFactory,
            $configMetadataFactory
        );
        $application = new App();
        $application->add($commandTmp);

        $sUT = $application->find('CodeGenerator:create-by-config');

        // We override the standard helper with our mock
        $sUT->getHelperSet()->set($dialog, 'dialog');

        $commandTester = new CommandTester($sUT);

        $commandTester->execute(array('command' => $sUT->getName()));
    }
}
