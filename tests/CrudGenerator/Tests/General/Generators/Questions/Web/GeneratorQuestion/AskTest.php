<?php
namespace CrudGenerator\Tests\General\Command\Questions\Web\GeneratorQuestion;

use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionIfNoResponseProvided()
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

    /**
     * @param string $class
     */
    private function createMock($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}
