<?php
namespace CrudGenerator\Tests\General\Generators\FormGenerator;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Utils\DiffPHP;
use CrudGenerator\Generators\FormGenerator\FormGenerator;
use CrudGenerator\Generators\FormGenerator\Form;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

class DoGenerateTest extends \PHPUnit_Framework_TestCase
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

        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubOutput->expects($this->any())
                   ->method('writeln')
                   ->will($this->returnValue(''));

        $generiqueQuestion = new GeneriqueQuestions($stubDialog, $stubOutput, new FileManager());
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();
        $fileConflictManager =  $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();
        $stubGeneratorDependencies =  $this->getMockBuilder('CrudGenerator\Generators\GeneratorDependencies')
        ->disableOriginalConstructor()
        ->getMock();


        $strategy = new GeneratorStrategy(ViewFactory::getInstance(), $stubOutput, $fileManager, $fileConflictManager);

        $metadata = new Form();
        $metadata->setEntity('TestZf2\Entities\NewsEntity')
                 ->setMetadata($this->getMetadata());

        $sUT = new FormGenerator(
            $stubOutput,
            $stubDialog,
            $generiqueQuestion,
            $strategy,
            $stubGeneratorDependencies
        );
        $sUT->generate($metadata);
    }

    private function getMetadata()
    {
        return include __DIR__ . '/../FakeMetaData.php';
    }
}
