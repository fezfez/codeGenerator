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
        $dto->setModule('src');
        $generatorDTO->setDTO($dto);

        $generatorToTest = clone $generatorDTO;

        $directories = array(
        	'dirOne',
        	'dirTwo'
        );
        $generatorToTest->addQuestion(
        	array(
        		'dtoAttribute'    => 'setModule',
        		'text'            => 'Select a Directory',
        		'placeholder'     => 'Actual directory "' . $dto->getModule() . '"',
        		'value'           => $dto->getModule(),
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
        ->with($dto->getModule() . '*', GLOB_ONLYDIR|GLOB_MARK)
        ->will($this->returnValue($directories));

        $sUT = new DirectoryQuestion($fileManagerStub);

        $this->assertEquals($generatorToTest, $sUT->ask($generatorDTO));
    }
}