<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Web\DirectoryQuestion;

use CrudGenerator\Generators\Questions\Web\DirectoryQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $context = $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context->expects($this->once())
        ->method('askCollection');

        $generatorDTO = new GeneratorDataObject();
        $dto          = new DataObject();
        $dto->register(array('dtoAttribute' => 'ModelDirectory'), false);
        $dto->setModelDirectory('src');
        $generatorDTO->setDto($dto);

        $directories = array(
            'dirOne',
            'dirTwo'
        );

        $fileManagerStub = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

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
        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

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

        $fileManagerStub = $this->getMockBuilder('\CrudGenerator\Utils\FileManager')
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
        $dto = new DataObject();
        $dto->register(array('dtoAttribute' => 'ModelDirectory'), false);
        $generatorDTO->setDto($dto);

        $generatorDTO = $sUT->ask(
            $generatorDTO,
            array('dtoAttribute' => 'ModelDirectory', 'text' => 'Good question...')
        );
        $this->assertEquals('myFile/', $generatorDTO->getDto()->getModelDirectory());
    }

    public function testCreateFileAndWithCliInstance()
    {
        $context         = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();
        $fileManagerStub = $this->getMockBuilder('\CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

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
                        'myFile2'
                    )
                ),
                $this->returnValue(array())
            )
        );

        $sUT = new DirectoryQuestion($fileManagerStub, $context);

        $generatorDTO = new GeneratorDataObject();
        $dto = new DataObject();
        $dto->register(array('dtoAttribute' => 'ModelDirectory'), false);
        $generatorDTO->setDto($dto);

        $generatorDTO = $sUT->ask(
            $generatorDTO,
            array('dtoAttribute' => 'ModelDirectory', 'text' => 'Good question...')
        );

        $this->assertEquals('MyTrueDir/', $generatorDTO->getDto()->getModelDirectory());
    }
}
