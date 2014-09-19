<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\FileParser;

use CrudGenerator\Generators\Parser\Lexical\FileParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\DataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new FileParser($fileManager, $conditionValidator);

        $generator = new GeneratorDataObject();

        $process = array();

        $this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
        $this->assertEquals(
            $generator,
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithFiles()
    {
        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->will($this->returnValue('MyFileParser'));

        $sUT = new FileParser($fileManager, $conditionValidator);

        $generator = new GeneratorDataObject();
        $generator->setPath('./');

        $process = array(
            'filesList' => array(
                array('templatePath' => 'MyFileTemplate', 'destinationPath' => 'MyFile')
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addFile($generator->getPath() . '/Skeleton/', 'MyFileTemplate', 'MyFileParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithDependencyCondiction()
    {
        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->will($this->returnValue('MyFileParser'));

        $sUT = new FileParser($fileManager, $conditionValidator);

        $generator = new GeneratorDataObject();
        $generator->setPath('./');

        $process = array(
            'filesList' => array(
                array(
                    'templatePath' => 'MyFileTemplate',
                    'destinationPath' => 'MyFile',
                    DependencyCondition::NAME => '!ArchitedGenerator'
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addFile($generator->getPath() . '/Skeleton/', 'MyFileTemplate', 'MyFileParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithEnvironnemetnCondiction()
    {
        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $conditionValidator->expects($this->once())
        ->method('isValid')
        ->will($this->returnValue(true));

        $phpParser = $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyFile')
        ->will($this->returnValue('MyFileParsed'));

        $sUT = new FileParser($fileManager, $conditionValidator);

        $generator = new GeneratorDataObject();
        $generator->setDto(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $process = array(
            'filesList' => array(
                array(
                    'templatePath' => 'MyFileTemplate',
                    'destinationPath' => 'MyFile',
                    EnvironnementCondition::NAME => 'backend == pdo'
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addFile($generator->getPath() . '/Skeleton/', 'MyFileTemplate', 'MyFileParsed'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }
}
