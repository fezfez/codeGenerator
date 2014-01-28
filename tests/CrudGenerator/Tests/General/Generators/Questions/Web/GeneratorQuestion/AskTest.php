<?php
namespace CrudGenerator\Tests\General\Command\Questions\Web\GeneratorQuestion;


use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
                         ->method('getAllClasses')
                         ->will(
                            $this->returnValue(
                                array(
                                    'path/ArchitectGenerator.generator.yaml' => 'ArchitectGenerator'
                                )
                            )
                        );


        $sUT = new GeneratorQuestion($sourceFinderStub);
        $this->assertEquals(array(array('id' => 'path/ArchitectGenerator.generator.yaml', 'label' => 'ArchitectGenerator')), $sUT->ask());
    }
}