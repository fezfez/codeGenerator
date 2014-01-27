<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\DirectoriesParser;

use CrudGenerator\Generators\Parser\Lexical\DirectoriesParser;
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
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new DirectoriesParser();

    	$generator = new GeneratorDataObject();

    	$process = array();

        $this->assertEquals(
            $generator,
            $sUT->evaluate($process, $phpParser, $generator, array(), true)
        );
    }

    public function testMalformedVar()
    {
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new DirectoriesParser();

    	$generator = new GeneratorDataObject();

    	$process = array(
    		'directories' => array(
    			array('MyVar' => 'MyValue')
    		)
    	);

		$this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
    	$sUT->evaluate($process, $phpParser, $generator, array(), true);
    }

    public function testWithFiles()
    {
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$phpParser->expects($this->once())
    	->method('parse')
    	->will($this->returnValue('MyDirParser'));


    	$sUT = new DirectoriesParser();

    	$generator = new GeneratorDataObject();

    	$process = array(
    		'directories' => array(
				'MyDir'
    		)
    	);

    	$this->assertEquals(
    		$generator->addDirectories('MyDir', 'MyDirParser'),
    		$sUT->evaluate($process, $phpParser, $generator, array(), true)
    	);
    }
}
