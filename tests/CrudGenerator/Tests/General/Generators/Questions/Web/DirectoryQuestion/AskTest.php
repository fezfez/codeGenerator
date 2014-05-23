<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Web\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Web\DirectoryQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context->expects($this->once())
        ->method('askCollection');

        $generatorDTO = new GeneratorDataObject();
        $dto = new Architect();
        $dto->setModelDirectory('src');
        $generatorDTO->setDTO($dto);

        $generatorToTest = clone $generatorDTO;

        $directories = array(
            'dirOne',
            'dirTwo'
        );

        $fileManagerStub =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManagerStub->expects($this->once())
        ->method('glob')
        ->with($dto->getModelDirectory() . '*', GLOB_ONLYDIR|GLOB_MARK)
        ->will($this->returnValue($directories));

        $sUT = new DirectoryQuestion($fileManagerStub, $context);

        $this->assertEquals($generatorDTO, $sUT->ask($generatorDTO, array('dtoAttribute' => 'ModelDirectory', 'text' => 'Select a Directory')));
    }
}