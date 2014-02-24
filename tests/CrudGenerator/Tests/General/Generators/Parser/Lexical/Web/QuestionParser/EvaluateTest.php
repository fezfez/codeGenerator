<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Web\QuestionParser;

use CrudGenerator\Generators\Parser\Lexical\Web\QuestionParser as WebQuestionParser;
use CrudGenerator\Generators\Parser\Lexical\QuestionParser;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Context\WebContext;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testMalformedVar()
    {
    	$dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
    	->disableOriginalConstructor()
    	->getMock();

    	$directoryQuestion =  $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\DirectoryQuestion')
    	->disableOriginalConstructor()
    	->getMock();

    	$app = $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($app);

    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new QuestionParser($context, $dependencyCondition, new WebQuestionParser());

    	$generator = new GeneratorDataObject();

    	$process = array(
    		'questions' => array(
    			'MyQuestion' => 'myQuestionValue'
    		)
    	);

		$this->setexpectedexception('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
    	$sUT->evaluate($process, $phpParser, $generator, array(), true);
    }

    public function testWithFiles()
    {
    	$dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
    	->disableOriginalConstructor()
    	->getMock();

    	$directoryQuestion =  $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\DirectoryQuestion')
    	->disableOriginalConstructor()
    	->getMock();

    	$app = $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($app);

    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new QuestionParser($context, $dependencyCondition, new WebQuestionParser());

    	$generator = new GeneratorDataObject();

    	$process = array(
    		'questions' => array(
    			array(
    				'dtoAttribute'    => 'test',
    				'text'            => 'test',
    				'defaultResponse' => 'myDefaultResponse'
    			)
    		)
    	);
    	$this->assertEquals(
    		$generator->addQuestion(
                array(
                    'dtoAttribute'    => 'setTest',
                    'text'            => 'test',
                    'value'           => '',
                    'defaultResponse' => 'myDefaultResponse'
                )
            ),
    		$sUT->evaluate($process, $phpParser, $generator, array(), false)
    	);
    }

    public function testWithDependencyCondiction()
    {
    	$dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
    	->disableOriginalConstructor()
    	->getMock();

    	$directoryQuestion =  $this->getMockBuilder('CrudGenerator\Generators\Questions\Web\DirectoryQuestion')
    	->disableOriginalConstructor()
    	->getMock();

    	$app = $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($app);

    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$phpParser->expects($this->once())
    	->method('parse')
    	->with('myDefaultResponse')
    	->will($this->returnValue('myDefaultResponse'));

    	$dependencyCondition->expects($this->once())
    	->method('evaluate')
    	->will($this->returnValue(
    		array(
	    		array(
	    			'dtoAttribute'    => 'test',
			    	'text'            => 'test',
			    	'defaultResponse' => 'myDefaultResponse'
	    		)
    		)
    	));

    	$sUT = new QuestionParser($context, $dependencyCondition, new WebQuestionParser());

    	$generator = new GeneratorDataObject();

    	$process = array(
    		'questions' => array(
    			array(
    				GeneratorParser::DEPENDENCY_CONDITION => array(
    					'!ArchitedGenerator' => array(
		    				'dtoAttribute'    => 'test',
		    				'text'            => 'test',
		    				'defaultResponse' => 'myDefaultResponse'
    					)
    				)
    			),
    			array(
    				'type'    => GeneratorParser::COMPLEX_QUESTION,
    				'factory' => 'CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionFactory'
    			)
    		)
    	);

    	$generatorToTest = clone $generator;

    	$this->assertEquals(
    		$generatorToTest->addQuestion(
                array(
                    'dtoAttribute'    => 'setTest',
                    'text'            => 'test',
                    'value'           => '',
                    'defaultResponse' => 'myDefaultResponse',
                	'required'        => false
                )
            ),
    		$sUT->evaluate($process, $phpParser, $generator, array(), false)
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
