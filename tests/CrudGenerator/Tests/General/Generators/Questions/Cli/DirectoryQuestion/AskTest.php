<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Cli\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Cli\DirectoryQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        // First choice bin
        $context->expects($this->exactly(3))
        ->method('askCollection')
        ->with(
            $this->isType('string'),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will($this->onConsecutiveCalls($this->returnValue(4), $this->returnValue(DirectoryQuestion::BACK), $this->returnValue(DirectoryQuestion::CURRENT_DIRECTORY)));

        $fileManagerStub =  $this->getMockBuilder('\CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManagerStub->expects($this->any())
        ->method('glob')
        ->will(
            $this->returnValue(
                array(
                    'mmydir/',
                    'myFile/',
                    'myFile2/'
                )
            )
        );

        $sUT = new DirectoryQuestion($fileManagerStub, $context);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $dto = $sUT->ask($generatorDTO, array('attribute' => 'ModelDirectory'))->getDTO();
        // or alternatively
        if ( ! $dto instanceof Architect) {
            throw new \LogicException('The instance must be "Architect"');
        }

        $this->assertEquals('myFile', $dto->getModelDirectory());
    }

    public function testOkWithCreateFile()
    {
        $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManagerStub =  $this->getMockBuilder('\CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $context->expects($this->any())
        ->method('log')
        ->will($this->returnValue(''));

        $context->expects($this->exactly(2))
        ->method('askCollection')
        ->with(
            $this->isType('string'),
            $this->isType('string'),
            $this->isType('array')
        )
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    2
                ),
                $this->returnValue(
                    1
                )
            )
        );

        $context->expects($this->exactly(2))
        ->method('ask')
        ->with(
            $this->isType('string'),
            $this->isType('string')
        )
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    'myFalseDir'
                ),
                $this->returnValue(
                    'MyTrueDir'
                )
            )
        );

        $fileManagerStub->expects($this->exactly(2))
        ->method('ifDirDoesNotExistCreate')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    false
                ),
                $this->returnValue(
                    true
                )
            )
        );

        $fileManagerStub->expects($this->exactly(2))
        ->method('glob')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(
                    array(
                        'mmydir',
                        'myFile',
                        'myFile2'
                    )
                ),
                $this->returnValue(
                    array()
                )
            )
        );

        $sUT = new DirectoryQuestion($fileManagerStub, $context);

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setDTO(new Architect());

        $dto = $sUT->ask($generatorDTO, array('attribute' => 'ModelDirectory'))->getDTO();
        // or alternatively
        if (!$dto instanceof Architect) {
            throw new \LogicException('The instance must be "Architect"');
        }

        $this->assertEquals('./MyTrueDir/', $dto->getModelDirectory());
    }
}
