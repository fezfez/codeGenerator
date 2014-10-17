<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetadataSource\MetadataSourceQuestion;

use CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\MetaData\MetaDataSource;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source                   = new MetaDataSource();

        $source->setDefinition('My definition')
               ->setMetadataDao('My name')
               ->setMetadataDaoFactory('My nameFactory');

        $metadataSourceCollection->append($source);

        $sourceWithFailedDependencie = new MetaDataSource();

        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setMetadataDao('My name')
        ->setFalseDependencie('My false dependencies')
        ->setMetadataDaoFactory('My nameFactory');

        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder')
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub->expects($this->once())
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetadataSourceQuestion($sourceFinderStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');
        $sUT->ask();
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

    public function testWithPreselectedOk()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source                   = new MetaDataSource();

        $source->setDefinition('My definition')
        ->setMetadataDao('My name')
        ->setMetadataDaoFactory('my name factory');

        $metadataSourceCollection->append($source);

        $sourceWithFailedDependencie = new MetaDataSource();

        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setMetadataDao('My named')
        ->setFalseDependencie('My false dependencies');

        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));

        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue($source));

        $sUT = new MetadataSourceQuestion($sourceFinderStub, $context);
        $this->assertEquals($source, $sUT->ask());
    }

    public function testWithPreselectedFail()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source                   = new MetaDataSource();

        $source->setDefinition('My definition')
        ->setMetadataDao('My name');

        $metadataSourceCollection->append($source);

        $sourceWithFailedDependencie = new MetaDataSource();
        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setMetadataDao('My name')
        ->setFalseDependencie('My false dependencies');

        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder')
        ->disableOriginalConstructor()
        ->getMock();

        $sourceFinderStub->expects($this->once())
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetadataSourceQuestion($sourceFinderStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');
        $sUT->ask();
    }
}
