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

        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, new FileManager());
        $strategy =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\GeneratorStrategy')
        ->disableOriginalConstructor()
        ->getMock();
        $stubGeneratorDependencies =  $this->getMockBuilder('CrudGenerator\Generators\GeneratorDependencies')
        ->disableOriginalConstructor()
        ->getMock();
        $stubMetaDataToArray =  $this->getMockBuilder('CrudGenerator\Generators\ArchitectGenerator\MetaDataToArray')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ArchitectGenerator(
            $stubOutput,
            $stubDialog,
            $generiqueQuestion,
            $strategy,
            $stubGeneratorDependencies,
        	$stubMetaDataToArray
        );

        return $sUT;
    }
}
