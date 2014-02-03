<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Web\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Web\DirectoryQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $generatorDTO = new GeneratorDataObject();
        $dto = new Architect();
        $dto->setModelDirectory('src');
        $generatorDTO->setDTO($dto);

        $generatorToTest = clone $generatorDTO;

        $directories = array(
        	'dirOne',
        	'dirTwo'
        );
        $generatorToTest->addQuestion(
        	array(
        		'dtoAttribute'    => 'setModelDirectory',
        		'text'            => 'Select a Directory',
        		'placeholder'     => 'Actual directory "' . $dto->getModelDirectory() . '"',
        		'value'           => $dto->getModelDirectory(),
        		'type'            => 'select',
        		'values'          => array(
        			array('label' => 'Back', 'id' => ''),
        			array('label' => 'dirOne', 'id' => 'dirOne'),
        			array('label' => 'dirTwo', 'id' => 'dirTwo')
        		)
        	)
        );

        $fileManagerStub =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManagerStub->expects($this->once())
        ->method('glob')
        ->with($dto->getModelDirectory() . '*', GLOB_ONLYDIR|GLOB_MARK)
        ->will($this->returnValue($directories));

        $sUT = new DirectoryQuestion($fileManagerStub);

        $this->assertEquals($generatorToTest, $sUT->ask($generatorDTO, array('dtoAttribute' => 'ModelDirectory', 'text' => 'Select a Directory')));
    }
}