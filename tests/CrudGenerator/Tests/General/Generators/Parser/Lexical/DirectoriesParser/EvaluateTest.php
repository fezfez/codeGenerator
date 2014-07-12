<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\DirectoriesParser;

use CrudGenerator\Generators\Parser\Lexical\DirectoriesParser;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;

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
            $sUT->evaluate($process, $phpParser, $generator, true)
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
    	$sUT->evaluate($process, $phpParser, $generator, true);
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
    		$sUT->evaluate($process, $phpParser, $generator, true)
    	);
    }
}
