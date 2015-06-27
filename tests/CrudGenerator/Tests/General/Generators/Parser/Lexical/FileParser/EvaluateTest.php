<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\FileParser;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\FileParser;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Utils\PhpStringParser;

class EvaluateTest extends TestCase
{
    public function testThrowExceptionWhenDirDoesNotExist()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $fileManager->expects($this->once())
        ->method('isDir')
        ->willReturn(false);

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenEmptyProcess()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileListIsNotAnArray()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array('filesList' => null);

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileInFileListIsNotAnArray()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array('filesList' => array(null));

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileInFileListIsHaveNoTemplatePath()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array(
            'filesList' => array(
                array('destinationPath' => '{{ test }}'),
            ),
        );

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileInFileListIsHaveNoDestinationPath()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array(
            'filesList' => array(
                array('templatePath' => '{{ test }}'),
            ),
        );

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileInFileListTemplate()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $phpParser          = new PhpStringParser(
            new \Twig_Environment(new \Twig_Loader_Array(array())), array('test' => 'well')
        );

        $fileManager->expects($this->once())
        ->method('isFile')
        ->willReturn(false);

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array(
            'filesList' => array(
                array('templatePath' => 'MyFileTemplate', 'destinationPath' => '{{ test }}'),
            ),
        );

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testFileDestinationAreWellParsed()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');
        $phpParser          = new PhpStringParser(
            new \Twig_Environment(new \Twig_Loader_Array(array())), array('test' => 'well')
        );

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->willReturn(true);

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array(
            'filesList' => array(
                array('templatePath' => 'MyFileTemplate', 'destinationPath' => '{{ test }}'),
            ),
        );

        $generator = $sUT->evaluate($process, $phpParser, $generator, true);

        $files = $generator->getFiles();

        $this->assertEquals(true, isset($files['well']));
    }

    public function testWithFiles()
    {
        $fileManager        = $this->createMock('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->createMock('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->createMock('CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator');

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser->expects($this->once())
        ->method('parse')
        ->will($this->returnValue('MyFileParser'));

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $generator->setPath('./');

        $process = array(
            'filesList' => array(
                array(
                    'templatePath'    => 'MyFileTemplate',
                    'destinationPath' => 'MyFile',
                ),
            ),
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addFile($generator->getPath() . '/Skeleton/', 'MyFileTemplate', 'MyFileParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }
}
