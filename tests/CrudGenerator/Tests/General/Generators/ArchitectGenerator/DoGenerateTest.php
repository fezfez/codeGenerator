<?php
namespace CrudGenerator\Tests\General\Generators\ArchitectGenerator;

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Utils\DiffPHP;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;


class DoGenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnType()
    {
        $metadata = new Architect();
        $metadata->setEntity('TestZf2\Entities\NewsEntity')
                ->setMetadata($this->getMetadata())
                ->setNamespace('namespace')
                ->setGenerateUnitTest(true);

        $sUT = $this->getSUT();

        $this->assertInstanceOf(
            'CrudGenerator\Generators\ArchitectGenerator\Architect',
            $sUT->generate($metadata)
        );

        $metadata = new Architect();
        $metadata->setEntity('TestZf2\Entities\NewsEntity')
                 ->setMetadata($this->getMetadata());

        $this->assertInstanceOf(
            'CrudGenerator\Generators\ArchitectGenerator\Architect',
            $sUT->generate($metadata)
        );
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

        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();
        $fileConflictManager =  $this->getMockBuilder('CrudGenerator\FileConflict\FileConflictManager')
        ->disableOriginalConstructor()
        ->getMock();

        $strategy = new GeneratorStrategy(ViewFactory::getInstance(), $stubOutput, $fileManager, $fileConflictManager);

        $sUT = new ArchitectGenerator(
            $stubOutput,
            $stubDialog,
            $generiqueQuestion,
            $strategy
        );

        return $sUT;
    }

    private function getMetadata()
    {
        return include __DIR__ . '/../FakeMetaData.php';
    }
}
