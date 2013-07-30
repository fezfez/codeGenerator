<?php
namespace CrudGenerator\Tests\General\Generators\BaseCodeGenerator;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Diff\DiffPHP;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;

use CrudGenerator\MetaData\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

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
                new MetaDataColumnDataObjectCollection(),
                new MetaDataRelationDataObjectCollection()
            )
        );

        $this->setExpectedException('RuntimeException');

        $sUT->generate($dataObject);
    }

    public function testFailOnNameIndentifier()
    {
        $sUT      = $this->getClass();
        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnDataObjectCollection(), new MetaDataRelationDataObjectCollection());
        $metadata->addIdentifier('toto');

        $dataObject = new Architect();
        $dataObject->setEntity('TestZf2\Entities\NewsEntity')
                   ->setMetadata($metadata);

        $this->setExpectedException('RuntimeException');

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

        //$stubOutput = new ConsoleOutput();

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('mkdir')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->any())
                        ->method('filePutsContent')
                        ->will($this->returnValue(true));

        $view              = ViewFactory::getInstance();
        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput);
        $diffPHP           = new DiffPHP();

        return new ArchitectGenerator(
            $view,
            $stubOutput,
            $stubFileManager,
            $stubDialog,
            $generiqueQuestion,
            $diffPHP
        );
    }

    private function getMetadata()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
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
