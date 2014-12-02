<?php
namespace CrudGenerator\Tests\General\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion;

use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion;
use CrudGenerator\Metadata\MetaDataSourceCollection;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\Sources\Doctrine2\Doctrine2MetaDataDAOFactory;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();

        $metadataSourceCollection->append(Doctrine2MetaDataDAOFactory::getDescription());

        $sourceWithFailedDependencie = Doctrine2MetaDataDAOFactory::getDescription();

        $sourceWithFailedDependencie->setFalseDependencie('My false dependencies');

        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $metadataConfigDAO = $this->createMock('CrudGenerator\Metadata\Config\MetaDataConfigDAO');

        $metadataConfigDAO->expects($this->once())
        ->method('retrieveAll')
        ->will($this->returnValue($metadataSourceCollection));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetadataSourceConfiguredQuestion($metadataConfigDAO, $context);

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
        $source                   = Doctrine2MetaDataDAOFactory::getDescription();

        $metadataSourceCollection->append($source);

        $sourceWithFailedDependencie = Doctrine2MetaDataDAOFactory::getDescription();

        $sourceWithFailedDependencie->setFalseDependencie('My false dependencies');

        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $metadataConfigDAO = $this->createMock('CrudGenerator\Metadata\Config\MetaDataConfigDAO');

        $metadataConfigDAO->expects($this->once())
        ->method('retrieveAll')
        ->will($this->returnValue($metadataSourceCollection));

        $context = $this->createMock('CrudGenerator\Context\CliContext');

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue($source));

        $sUT = new MetadataSourceConfiguredQuestion($metadataConfigDAO, $context);

        $this->assertEquals($source, $sUT->ask());
    }

    public function testWithPreselectedFail()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source                   = Doctrine2MetaDataDAOFactory::getDescription();

        $metadataSourceCollection->append($source);

        $sourceWithFailedDependencie = Doctrine2MetaDataDAOFactory::getDescription();
        $sourceWithFailedDependencie->setFalseDependencie('My false dependencies');

        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $metadataConfigDAO = $this->createMock('CrudGenerator\Metadata\Config\MetaDataConfigDAO');

        $metadataConfigDAO->expects($this->once())
        ->method('retrieveAll')
        ->will($this->returnValue($metadataSourceCollection));

        $context = new \CrudGenerator\Context\CliContext(
            $this->createMock('Symfony\Component\Console\Helper\QuestionHelper'),
            $this->createMock('Symfony\Component\Console\Output\OutputInterface'),
            $this->createMock('Symfony\Component\Console\Input\InputInterface'),
            $this->createMock('CrudGenerator\Command\CreateCommand')
        );

        $sUT = new MetadataSourceConfiguredQuestion($metadataConfigDAO, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');
        $sUT->ask();
    }
}
