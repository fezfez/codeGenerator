<?php
namespace CrudGenerator\Tests\General\Generators\ArchitectGenerator;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Diff\DiffPHP;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use CrudGenerator\DataObject;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

use CrudGenerator\MetaData\Doctrine2\Doctrine2MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;

class doGenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
                   ->method('askAndValidate')
                   ->will($this->returnValue(__DIR__));

        $stubDialog->expects($this->any())
                   ->method('ask')
                   ->will($this->returnValue('y'));

        $stubOutput = $this->getMock('\Symfony\Component\Console\Output\ConsoleOutput');
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

        $dialog            = new DialogHelper();
        $output            = new ConsoleOutput();
        $input             = new ArgvInput();
        $view              = ViewFactory::getInstance();
        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput);
        $diffPHP           = new DiffPHP();


        $metadata = new DataObject();
        $metadata->setEntity('TestZf2\Entities\NewsEntity')
                 ->setMetadata($this->getMetadata());

        $sUT = new ArchitectGenerator($view, $stubOutput, $stubFileManager, $stubDialog, $input, $generiqueQuestion, $diffPHP);
        $sUT->generate($metadata);
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

