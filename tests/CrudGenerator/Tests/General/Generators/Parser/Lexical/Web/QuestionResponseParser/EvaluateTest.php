<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Web\QuestionResponseParser;

use CrudGenerator\Generators\Parser\Lexical\Web\QuestionResponseParser;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;


class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$generator = new GeneratorDataObject();
    	$generator->setDTO(new Architect());

    	$sUT = new QuestionResponseParser();

    	$response = array(
    		'setAttributeName' => array(
    			'myKey' => 'myValue'
    		),
    		'setNamespace' => 'myNamespace'
        );

    	$generator = $sUT->evaluate(array(), $phpParser, $generator, $response, true);

        $this->assertEquals(
            'myNamespace',
        	$generator->getDTO()->getNamespace()
        );

        $this->assertEquals(
        	'myValue',
        	$generator->getDTO()->getAttributeName('myKey')
        );
    }
}
