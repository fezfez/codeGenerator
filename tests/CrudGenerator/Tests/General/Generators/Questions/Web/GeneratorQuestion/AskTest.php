<?php
namespace CrudGenerator\Tests\General\Command\Questions\Web\GeneratorQuestion;

use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\Context\WebContext;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
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

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $sUT->ask();
    }
}
