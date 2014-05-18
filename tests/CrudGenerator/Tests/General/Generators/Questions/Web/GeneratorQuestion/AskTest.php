<?php
namespace CrudGenerator\Tests\General\Command\Questions\Web\GeneratorQuestion;

use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\Context\WebContext;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->exactly(2))
                         ->method('getAllClasses')
                         ->will(
                            $this->returnValue(
                                array(
                                    'path/ArchitectGenerator.generator.yaml' => 'ArchitectGenerator'
                                )
                            )
                        );

    	$context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
    	->disableOriginalConstructor()
    	->getMock();

        $sUT = new GeneratorQuestion($sourceFinderStub, $context);

        $this->setExpectedException('InvalidArgumentException');

        $sUT->ask();
        //$this->assertEquals(array(array('id' => 'path/ArchitectGenerator.generator.yaml', 'label' => 'ArchitectGenerator')), );
    }
}