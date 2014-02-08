<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Web\EnvironnementParser;

use CrudGenerator\Generators\Parser\Lexical\Web\EnvironnementParser;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\WebContext;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
	public function testEnvironnementMalFormed()
	{
		$app = $this->getMockBuilder('Silex\Application')
		->disableOriginalConstructor()
		->getMock();

		$context = new WebContext($app);

		$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
		->disableOriginalConstructor()
		->getMock();

		$generator = new GeneratorDataObject();
		$generator->setDTO(new Architect());

		$sUT = new EnvironnementParser($context);

		$string = 'environnement :
    framework :
        "zend_framework_2"';
		$process = Yaml::parse($string, true);

		$this->setExpectedException('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');

		$sUT->evaluate($process, $phpParser, $generator, array(), true);
	}

    public function testEnvironnement()
    {
    	$app = $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($app);

    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$generator = new GeneratorDataObject();
    	$generator->setDTO(new Architect());

    	$sUT = new EnvironnementParser($context);

    	$string = 'environnement :
    framework :
        "zend_framework_2" :
            backend : [pdo, doctrine2]
            template : [phtml]
        symfony2 :
            backend : [doctrine2]
            template : [twig]';
    	$process = Yaml::parse($string, true);


    	$questions = array(
    	    'environnement_framework' => 'zend_framework_2',
    	    'environnement_backend' => 'pdo'
    	);
        $generator = $sUT->evaluate($process, $phpParser, $generator, $questions, true);

        $this->assertEquals(
            array('framework' => 'zend_framework_2', 'backend' => 'pdo'),
            $generator->getEnvironnementCollection()
        );
    }
}
