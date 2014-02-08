<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\DependencyCondition;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use Symfony\Component\Yaml\Yaml;


class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testDifferent()
    {
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new DependencyCondition();

    	$generator = new GeneratorDataObject();

    	$string = '
questions :
    - dependencyCondition :
        - !ArchitectGenerator :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

    	$result = Yaml::parse($string, true);
    	foreach ($result['questions'] as  $files) {
			foreach ($files as $templateName => $tragetFile) {
	    		if ($templateName === 'dependencyCondition') {
			        $this->assertEquals(
			            array(array('/form/DataObject.phtml' => '<?php $formGenerator->getFormPath(); ?>DataObject.phtml')),
			            $sUT->evaluate($tragetFile, $phpParser, $generator, array(), true)
			        );
	    		}
			}
    	}
    }

    public function testIn()
    {
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new DependencyCondition();

    	$generator = new GeneratorDataObject();
    	$generator->addDependency('ArchitectGenerator');

    	$string = '
questions :
    - dependencyCondition :
        - ArchitectGenerator :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

    	$result = Yaml::parse($string, true);
    	foreach ($result['questions'] as  $files) {
    		foreach ($files as $templateName => $tragetFile) {
    			if ($templateName === 'dependencyCondition') {
    				$this->assertEquals(
    						array(array('/form/DataObject.phtml' => '<?php $formGenerator->getFormPath(); ?>DataObject.phtml')),
    						$sUT->evaluate($tragetFile, $phpParser, $generator, array(), true)
    				);
    			}
    		}
    	}
    }

    public function testWithout()
    {
    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$sUT = new DependencyCondition();

    	$generator = new GeneratorDataObject();

    	$string = '
questions :
    - dependencyCondition :
        - ArchitectGenerator :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

    	$result = Yaml::parse($string, true);
    	foreach ($result['questions'] as  $files) {
    		foreach ($files as $templateName => $tragetFile) {
    			if ($templateName === 'dependencyCondition') {
    				$this->assertEquals(
    						array(),
    						$sUT->evaluate($tragetFile, $phpParser, $generator, array(), true)
    				);
    			}
    		}
    	}
    }
}
