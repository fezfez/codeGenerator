<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Directory\DirectoryQuestion;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Questions\Directory\DirectoryQuestion;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    public function testOk()
    {
        $context = $this->createMock('CrudGenerator\Context\CliContext');

        $context->expects($this->once())
        ->method('askCollection');

        $generatorDTO = new GeneratorDataObject();
        $dto          = new DataObject();
        $dto->register(array('dtoAttribute' => 'ModelDirectory'), false);
        $dto->setModelDirectory('src');
        $generatorDTO->setDto($dto);

        $directories = array(
            'dirOne',
            'dirTwo',
        );

        $fileManagerStub = $this->createMock('CrudGenerator\Utils\FileManager');

        $fileManagerStub->expects($this->once())
        ->method('glob')
        ->with($dto->getModelDirectory() . '*', GLOB_ONLYDIR | GLOB_MARK)
        ->will($this->returnValue($directories));

        $sUT = new DirectoryQuestion($fileManagerStub, $context);

        $this->assertEquals(
            $generatorDTO,
            $sUT->ask($generatorDTO, array('dtoAttribute' => 'ModelDirectory', 'text' => 'Select a Directory'))
        );
    }

    public function testOkWithCliInstance()
    {
        $context = $this->createMock('CrudGenerator\Context\CliContext');

        // First choice bin
        $context->expects($this->exactly(4))
        ->method('askCollection')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue('mmydir/'),
                $this->returnValue(DirectoryQuestion::BACK),
                $this->returnValue('myFile/'),
                $this->returnValue(DirectoryQuestion::CURRENT_DIRECTORY)
            )
        );

        $fileManagerStub = $this->createMock('\CrudGenerator\Utils\FileManager');

        $fileManagerStub->expects($this->any())
        ->method('glob')
        ->will(
            $this->returnValue(
                array(
                    'mmydir/',
                    'myFile/',
                    'myFile2/',
                )
            )
        );

        $sUT          = new DirectoryQuestion($fileManagerStub, $context);
        $generatorDTO = new GeneratorDataObject();
        $dto          = new DataObject();

        $dto->register(array('dtoAttribute' => 'ModelDirectory'), false);
        $generatorDTO->setDto($dto);

        $generatorDTO = $sUT->ask(
            $generatorDTO,
            array('dtoAttribute' => 'ModelDirectory', 'text' => 'Good question...')
        );

        $this->assertEquals('myFile/', $generatorDTO->getDto()->getModelDirectory());
    }

    public function testCreateFileWithCliInstance()
    {
        $context         = $this->createMock('CrudGenerator\Context\CliContext');
        $fileManagerStub = $this->createMock('\CrudGenerator\Utils\FileManager');

        $context->expects($this->any())
        ->method('log')
        ->will($this->returnValue(''));

        $context->expects($this->exactly(2))
        ->method('askCollection')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(DirectoryQuestion::CREATE_DIRECTORY),
                $this->returnValue(DirectoryQuestion::CURRENT_DIRECTORY)
            )
        );

        $context->expects($this->exactly(2))
        ->method('ask')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue('myFalseDir'),
                $this->returnValue('MyTrueDir')
            )
        );

        $fileManagerStub->expects($this->exactly(2))
        ->method('ifDirDoesNotExistCreate')
        ->will(
            $this->onConsecutiveCalls(
                $this->returnValue(false),
                $this->returnValue(true)
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
                        'myFile2',
                    )
                ),
                $this->returnValue(array())
            )
        );

        $sUT          = new DirectoryQuestion($fileManagerStub, $context);
        $generatorDTO = new GeneratorDataObject();
        $dto          = new DataObject();

        $dto->register(array('dtoAttribute' => 'ModelDirectory'), false);
        $generatorDTO->setDto($dto);

        $generatorDTO = $sUT->ask(
            $generatorDTO,
            array('dtoAttribute' => 'ModelDirectory', 'text' => 'Good question...')
        );

        $this->assertEquals('MyTrueDir/', $generatorDTO->getDto()->getModelDirectory());
    }
}
