<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorDataObject;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class MethodTest extends \PHPUnit_Framework_TestCase
{
    public function testMethods()
    {
        $sUT = new GeneratorDataObject();
        $sUT->setDTO(new Architect());

        $questions = array('dtoAttribute' => 'test');

        $sUT->addQuestion($questions);

        $this->assertEquals(
            array($questions),
            $sUT->getQuestion()
        );

        $this->assertEquals(
            '{"templateVariable":[],"files":[],"directories":[],"name":null,"questions":[{"dtoAttribute":"test"}],"environnement":[]}',
            json_encode($sUT)
        );

        $sUT->addEnvironnementValue('backend', 'doctrine2');

        $this->assertEquals(
        	array('backend' => 'doctrine2'),
        	$sUT->getEnvironnementCollection()
        );
    }

    public function testFailAddEnvOnEmptyDTO()
    {
    	$sUT = new GeneratorDataObject();

    	$this->setExpectedException('LogicException');

    	$sUT->addEnvironnementValue('backend', 'doctrine2');
    }
}
