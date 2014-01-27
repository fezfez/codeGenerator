<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\FileParser;

use CrudGenerator\Generators\Parser\Lexical\FileParser;
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


    	$sUT = new FileParser($fileManager, $dependencyCondition, $environnementCondition);

    	$generator = new GeneratorDataObject();

    	$process = array();

    	$this->setexpectedexception('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
        $this->assertEquals(
            $generator,
            $sUT->evaluate($process, $phpParser, $generator, array(), true)
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


    	$sUT = new FileParser($fileManager, $dependencyCondition, $environnementCondition);

    	$generator = new GeneratorDataObject();

    	$process = array(
    			'filesList' => array(
    				'MyVar' => 'MyValue'
    			)
    	);

		$this->setexpectedexception('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
    	$sUT->evaluate($process, $phpParser, $generator, array(), true);
    }

    public function testWithFiles()
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
    	->will($this->returnValue('MyFileParser'));


    	$sUT = new FileParser($fileManager, $dependencyCondition, $environnementCondition);

    	$generator = new GeneratorDataObject();
    	$generator->setPath('./');

    	$process = array(
    		'filesList' => array(
				array('MyFileTemplate' => 'MyFile')
    		)
    	);

    	$this->assertEquals(
    		$generator->addFile(dirname($generator->getPath()) . '/Skeleton/', 'MyFileTemplate', 'MyFile', 'MyFileParser'),
    		$sUT->evaluate($process, $phpParser, $generator, array(), true)
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
    	->will($this->returnValue(array(array('MyFileTemplate' => 'MyFile'))));

    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();


    	$sUT = new FileParser($fileManager, $dependencyCondition, $environnementCondition);

    	$generator = new GeneratorDataObject();
    	$generator->setPath('./');

    	$process = array(
    		'filesList' => array(
    			array(
    				GeneratorParser::DEPENDENCY_CONDITION => array(
	    				'!ArchitedGenerator' => array(
	    					array('MyFileTemplate' => 'MyFile')
	    				)
    				)
    			)
    		)
    	);

    	$this->assertEquals(
    		$generator->addFile(dirname($generator->getPath()) . '/Skeleton/', 'MyFileTemplate', 'MyFile', 'MyFileParser'),
    		$sUT->evaluate($process, $phpParser, $generator, array(), true)
    	);
    }

    /*public function testWithEnvironnemetnCondiction()
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


    	$sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

    	$generator = new GeneratorDataObject();

    	$process = array(
    		'templateVariables' => array(
    			array(
    				GeneratorParser::ENVIRONNEMENT_CONDITION => array(
    	    			'!ArchitedGenerator' => array(
    	    				array('MyVar' => 'MyValue')
    	    			)
    				)
    			)
    		)
    	);

    	$this->assertEquals(
    			$generator->addTemplateVariable('MyVar', 'MyValue'),
    			$sUT->evaluate($process, $phpParser, $generator, array(), true)
    	);
    }*/
}
