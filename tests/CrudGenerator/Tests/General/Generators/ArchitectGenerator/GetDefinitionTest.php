<?php
namespace CrudGenerator\Tests\General\Generators\ArchitectGenerator;

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

class GetDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = $this->getSUT();
        $sUT->getDefinition();
    }

    private function getSUT()
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
        $diffPHP           = new DiffPHP();

        $sUT = new ArchitectGenerator(
            $view,
            $stubOutput,
            $stubFileManager,
            $stubDialog,
            $generiqueQuestion,
            $diffPHP
        );

        return $sUT;
    }
}
