<?php
namespace CrudGenerator\Tests\General\Command\Questions\Generator\GeneratorQuestion;

use CrudGenerator\Generators\Questions\Generator\GeneratorQuestion;
use CrudGenerator\Metadata\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Tests\TestCase;

class AskTest extends TestCase
{
    public function testExceptionIfNoResponseProvided()
    {
        $sourceFinderStub = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');

        $sourceFinderStub->expects($this->exactly(1))
                         ->method('getAllClasses')
                         ->will(
                            $this->returnValue(
                                array(
                                    'path/ArchitectGenerator.generator.yaml' => 'ArchitectGenerator',
                                )
                            )
                        );

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new GeneratorQuestion($sourceFinderStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');

        $metadata = new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->ask($metadata);
    }
}
