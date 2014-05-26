<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Web\MetaDataSourcesQuestion;

use CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\Context\WebContext;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
               ->setMetaDataDAO('My name')
               ->setMetaDataDAOFactory('My nameFactory');
        $metadataSourceCollection->append($source);
        $sourceWithFailedDependencie = new MetaDataSource();
        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setMetaDataDAO('My name')
        ->setFalseDependencie('My false dependencies')
        ->setMetaDataDAOFactory('My nameFactory');
        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub->expects($this->exactly(2))
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));

    	$context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
    	->disableOriginalConstructor()
    	->getMock();


        $sUT = new MetaDataSourcesQuestion($sourceFinderStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');
        $sUT->ask();
    }

    public function testWithPreselected()
    {

    	$metadataSourceCollection = new MetaDataSourceCollection();
    	$source = new MetaDataSource();
    	$source->setDefinition('My definition')
    	->setMetaDataDAO('My name')
    	->setMetaDataDAOFactory('my name factory');
    	$metadataSourceCollection->append($source);
    	$sourceWithFailedDependencie = new MetaDataSource();
    	$sourceWithFailedDependencie->setDefinition('My definition')
    	->setMetaDataDAO('My named')
    	->setFalseDependencie('My false dependencies');
    	$metadataSourceCollection->append($sourceWithFailedDependencie);

    	$sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder', array('select'))
    	->disableOriginalConstructor()
    	->getMock();
    	$sourceFinderStub->expects($this->exactly(2))
    	->method('getAllAdapters')
    	->will($this->returnValue($metadataSourceCollection));

        $context = $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue('my name factory'));

    	$sUT = new MetaDataSourcesQuestion($sourceFinderStub, $context);
    	$this->assertEquals($source, $sUT->ask());
    }

    public function testWithPreselectedFail()
    {

        $metadataSourceCollection = new MetaDataSourceCollection();
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setMetaDataDAO('My name');
        $metadataSourceCollection->append($source);
        $sourceWithFailedDependencie = new MetaDataSource();
        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setMetaDataDAO('My name')
        ->setFalseDependencie('My false dependencies');
        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub->expects($this->exactly(2))
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));

        $context = $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $context
        ->expects($this->once())
        ->method('askCollection')
        ->will($this->returnValue('My name'));

        $sUT = new MetaDataSourcesQuestion($sourceFinderStub, $context);

        $this->setExpectedException('CrudGenerator\Generators\ResponseExpectedException');
        $sUT->ask();
    }
}