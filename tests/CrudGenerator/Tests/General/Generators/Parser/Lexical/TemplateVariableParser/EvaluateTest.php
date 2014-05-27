<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\TemplateVariableParser;

use CrudGenerator\Generators\Parser\Lexical\TemplateVariableParser;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();


        $sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

        $generator = new GeneratorDataObject();

        $process = array();

        $this->assertEquals(
            $generator,
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testMalformedVar()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();


        $sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

        $generator = new GeneratorDataObject();

        $process = array(
                'templateVariables' => array(
                    'MyVar' => 'MyValue'
                )
        );

        $this->setexpectedexception('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
        $sUT->evaluate($process, $phpParser, $generator, true);
    }

    public function testWithVar()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyValue')
        ->will($this->returnValue('MyValueParser'));


        $sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

        $generator = new GeneratorDataObject();

        $process = array(
            'templateVariables' => array(
                array('MyVar' => 'MyValue')
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addTemplateVariable('MyVar', 'MyValueParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithDependencyCondiction()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition->expects($this->once())
        ->method('evaluate')
        ->will($this->returnValue(array(array('MyVar' => 'MyValue'))));

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyValue')
        ->will($this->returnValue('MyValueParser'));


        $sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

        $generator = new GeneratorDataObject();

        $process = array(
            'templateVariables' => array(
                array(
                    GeneratorParser::DEPENDENCY_CONDITION => array(
                        '!ArchitedGenerator' => array(
                            array('MyVar' => 'MyValue')
                        )
                    )
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addTemplateVariable('MyVar', 'MyValueParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }

    public function testWithEnvironnemetnCondiction()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition->expects($this->once())
        ->method('evaluate')
        ->will($this->returnValue(array(array('MyVar' => 'MyValue'))));

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyValue')
        ->will($this->returnValue('MyValueParser'));


        $sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

        $generator = new GeneratorDataObject();


        $process = array(
            'templateVariables' => array(
                array(
                    GeneratorParser::ENVIRONNEMENT_CONDITION => array(
                        'backend == pdo' => array(
                            array('MyVar' => 'MyValue')
                        )
                    )
                )
            )
        );

        $generatorToTest = clone $generator;

        $this->assertEquals(
            $generatorToTest->addTemplateVariable('MyVar', 'MyValueParser'),
            $sUT->evaluate($process, $phpParser, $generator, true)
        );
    }
}
