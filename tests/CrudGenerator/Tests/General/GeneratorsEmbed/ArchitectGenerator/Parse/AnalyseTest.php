<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\ArchitectGenerator\Parse;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\Parser\ParserCollectionFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;

class AnalyseTest extends \PHPUnit_Framework_TestCase
{
    public function testPDOWithZend2()
    {
    	$app = $this->getMockBuilder('Silex\Application')
		->disableOriginalConstructor()
		->getMock();

		$context = new WebContext($app);

		$generator = new GeneratorDataObject();
		$generator->setName('ArchitectGenerator');

        $generatorParser = GeneratorParserFactory::getInstance($context);

        $generator = $generatorParser->init(
            $generator,
            $this->getMetadata(),
            array('environnement_backend' => 'PDO', 'environnement_framework' => 'zend_framework_2')
        );

        $generator->getDTO()->setAttributeName('tetze', 'myName')->setAttributeName('myDate', 'madata');

        $fileGenerator = GeneratorStrategyFactory::getInstance($context);

        foreach ($generator->getFiles() as $file) {
        	$this->assertInternalType(
				'string',
	             $fileGenerator->generateFile(
	                 $generator->getTemplateVariables(),
	                 $file['skeletonPath'],
	                 $file['name'],
	                 $file['fileName']
	             )
        	);
        }
    }

    public function testOkDoctrine2WithZend2()
    {
    	$app = $this->getMockBuilder('Silex\Application')
    	->disableOriginalConstructor()
    	->getMock();

    	$context = new WebContext($app);

    	$generator = new GeneratorDataObject();
    	$generator->setName('ArchitectGenerator');

    	$generatorParser = GeneratorParserFactory::getInstance($context);

    	$generator = $generatorParser->init(
    			$generator,
    			$this->getMetadata(),
    			array('environnement_backend' => 'doctrine2', 'environnement_framework' => 'zend_framework_2')
    	);

    	$generator->getDTO()->setAttributeName('tetze', 'myName')->setAttributeName('myDate', 'madata');

    	$fileGenerator = GeneratorStrategyFactory::getInstance($context);

    	foreach ($generator->getFiles() as $file) {
    		$this->assertInternalType(
    				'string',
    				$fileGenerator->generateFile(
    						$generator->getTemplateVariables(),
    						$file['skeletonPath'],
    						$file['name'],
    						$file['fileName']
    				)
    		);
    	}
    }

    private function getMetadata()
    {
        return include __DIR__ . '/../../FakeMetaData.php';
    }
}
