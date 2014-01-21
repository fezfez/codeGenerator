<?php
namespace CrudGenerator\Tests\General\Generators\ArchitectGenerator;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\DialogHelper;

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
