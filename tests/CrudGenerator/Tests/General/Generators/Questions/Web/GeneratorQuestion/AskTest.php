<?php
namespace CrudGenerator\Tests\General\Command\Questions\Web\GeneratorQuestion;

use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\Context\WebContext;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->exactly(1))
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

        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->ask($metadata);
    }
}
