<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\FileParser;

use CrudGenerator\Generators\Parser\Lexical\FileParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\DataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockWithoutConstructor($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }

    public function testThrowExceptionWhenEmptyProcess()
    {
        $fileManager        = $this->getMockWithoutConstructor('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->getMockWithoutConstructor('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->getMockWithoutConstructor(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        );

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileListIsNotAnArray()
    {
        $fileManager        = $this->getMockWithoutConstructor('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->getMockWithoutConstructor('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->getMockWithoutConstructor(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        );

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array('filesList' => null);

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testThrowExceptionWhenFileInFileListIsNotAnArray()
    {
        $fileManager        = $this->getMockWithoutConstructor('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->getMockWithoutConstructor('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->getMockWithoutConstructor(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        );

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array('filesList' => array(null));

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testFileDestinationAreWellParsed()
    {
        $fileManager        = $this->getMockWithoutConstructor('CrudGenerator\Utils\FileManager');
        $phpParser          = new \CrudGenerator\Utils\PhpStringParser(
            new \Twig_Environment(new \Twig_Loader_String()), array('test' => 'well')
        );
        $conditionValidator = $this->getMockWithoutConstructor(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        );

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->willReturn(true);

        $sUT       = new FileParser($fileManager, $conditionValidator, new IteratorValidator($conditionValidator));
        $generator = new GeneratorDataObject();

        $process = array(
            'filesList' => array(
                array('templatePath' => 'MyFileTemplate', 'destinationPath' => '{{ test }}')
            )
        );

        $generator = $sUT->evaluate($process, $phpParser, $generator, true);

        $files = $generator->getFiles();

        $this->assertEquals(true, isset($files['well']));
    }

    public function testWithFiles()
    {
        $fileManager        = $this->getMockWithoutConstructor('CrudGenerator\Utils\FileManager');
        $phpParser          = $this->getMockWithoutConstructor('CrudGenerator\Utils\PhpStringParser');
        $conditionValidator = $this->getMockWithoutConstructor(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        );

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
                    'templatePath' => 'MyFileTemplate',
                    'destinationPath' => 'MyFile'
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addFile($generator->getPath() . '/Skeleton/', 'MyFileTemplate', 'MyFileParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }
}
