<?php
namespace CrudGenerator\Tests\General\Generators\BaseCodeGenerator;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Utils\DiffPHP;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;

use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testFailOnMetadata()
    {
        $sUT      = $this->getClass();
        $metadata = $this->getMetadata();

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity');

        $this->setExpectedException('RuntimeException');

        $sUT->generate($dataObject);
    }

    public function testFailOnIndentifier()
    {
        $sUT      = $this->getClass();

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
                   ->setMetadata(new MetadataDataObjectDoctrine2(
                new MetaDataColumnCollection(),
                new MetaDataRelationCollection()
            )
        );

        $this->setExpectedException('RuntimeException');

        $sUT->generate($dataObject);
    }

   public function testFailOnNameIndentifier()
    {
        $sUT      = $this->getClass();
        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $metadata->addIdentifier('toto');

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
                   ->setMetadata($metadata);

        $this->setExpectedException('RuntimeException');

        $sUT->generate($dataObject);
    }

    public function testStub()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
        ->method('askAndValidate')
        ->will($this->returnValue(__DIR__));

        $stubDialog->expects($this->any())
        ->method('ask')
        ->will($this->returnValue('y'));

        $stubDialog->expects($this->any())
        ->method('select')
        ->will($this->returnValue('0'));

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $stubOutput->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $stubFileManager = $this->getMockBuilder('\CrudGenerator\Utils\FileManagerStub')
        ->disableOriginalConstructor()
        ->getMock();
        $stubFileManager->expects($this->any())
        ->method('mkdir')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('filePutsContent')
        ->will($this->onConsecutiveCalls($this->throwException(new \Exception())));
        $stubFileManager->expects($this->any())
        ->method('isFile')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('unlink')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('fileGetContent')
        ->will($this->returnValue(''));

        $view              = ViewFactory::getInstance();
        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, new FileManager());
        $FileConflictManager = $this->getMockBuilder('\CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ArchitectGenerator(
            $view,
            $stubOutput,
            $stubFileManager,
            $stubDialog,
            $generiqueQuestion,
            $FileConflictManager
        );

        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $metadata->addIdentifier('id');
        $column = new MetaDataColumn();
        $column->setLength(10)
        ->setName('toto')
        ->setNullable(true)
        ->setType('text');

        $metadata->appendColumn($column);

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
        ->setMetadata($metadata);

        $sUT->generate($dataObject);
    }

    public function testAlreadyExistAndPOSTPONE()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
        ->method('askAndValidate')
        ->will($this->returnValue(__DIR__));

        $stubDialog->expects($this->any())
        ->method('ask')
        ->will($this->returnValue('y'));

        $stubDialog->expects($this->any())
        ->method('select')
        ->will($this->returnValue('0'));

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $stubOutput->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
        ->method('mkdir')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('filePutsContent')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('isFile')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('unlink')
        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
        ->method('fileGetContent')
        ->will($this->returnValue(''));
        $stubFileManager->expects($this->any())
        ->method('ifDirDoesNotExistCreate')
        ->will($this->onConsecutiveCalls(true));

        $view              = ViewFactory::getInstance();
        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, new FileManager());
        $FileConflictManager = $this->getMockBuilder('\CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();
        $FileConflictManager->expects($this->any())
        ->method('test')
        ->will($this->onConsecutiveCalls(true));
        $FileConflictManager->expects($this->once())
        ->method('handle');

        $sUT = new ArchitectGenerator(
            $view,
            $stubOutput,
            $stubFileManager,
            $stubDialog,
            $generiqueQuestion,
            $FileConflictManager
        );

        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());
        $metadata->addIdentifier('id');
        $column = new MetaDataColumn();
        $column->setLength(10)
               ->setName('toto')
               ->setNullable(true)
               ->setType('text');

        $metadata->appendColumn($column);

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
        ->setMetadata($metadata);

        $sUT->generate($dataObject);
    }

    /**
     * @return \CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator
     */
    private function getClass()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
                   ->method('askAndValidate')
                   ->will($this->returnValue(__DIR__));

        $stubDialog->expects($this->any())
                   ->method('ask')
                   ->will($this->returnValue('y'));

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubOutput->expects($this->any())
                   ->method('writeln')
                   ->will($this->returnValue(''));

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('mkdir')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
                        ->method('filePutsContent')
                        ->will($this->returnValue(true));

        $view              = ViewFactory::getInstance();
        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, new FileManager());
        $FileConflictManager = $this->getMockBuilder('\CrudGenerator\FileConflict\FileConflictManager')
                            ->disableOriginalConstructor()
                            ->getMock();

        return new ArchitectGenerator(
            $view,
            $stubOutput,
            $stubFileManager,
            $stubDialog,
            $generiqueQuestion,
            $FileConflictManager
        );
    }

    /**
     * @return \CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2
     */
    private function getMetadata()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue(include __DIR__ . '/../../../ZF2/config/application.config.php'));

        $sm = ZendFramework2Environnement::getDependence($stubFileManager);
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $suT = new Doctrine2MetaDataDAO($em);

        return $suT->getMetadataFor('TestZf2\Entities\NewsEntity');
    }
}
