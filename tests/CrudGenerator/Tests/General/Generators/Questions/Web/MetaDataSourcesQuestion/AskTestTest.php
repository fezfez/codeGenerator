<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Web\MetaDataSourcesQuestion;


use CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\MetaData\MetaDataSource;


class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $metadataSourceCollection = new MetaDataSourceCollection();
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
               ->setName('My name');
        $metadataSourceCollection->append($source);
        $sourceWithFailedDependencie = new MetaDataSource();
        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setName('My name')
        ->setFalseDependencie('My false dependencies');
        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub->expects($this->once())
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));


        $sUT = new MetaDataSourcesQuestion($sourceFinderStub);
        $this->assertEquals(array(array('id' => 'My nameFactory', 'label' => 'My definition')), $sUT->ask());
    }

    public function testWithPreselected()
    {

    	$metadataSourceCollection = new MetaDataSourceCollection();
    	$source = new MetaDataSource();
    	$source->setDefinition('My definition')
    	->setName('My name');
    	$metadataSourceCollection->append($source);
    	$sourceWithFailedDependencie = new MetaDataSource();
    	$sourceWithFailedDependencie->setDefinition('My definition')
    	->setName('My named')
    	->setFalseDependencie('My false dependencies');
    	$metadataSourceCollection->append($sourceWithFailedDependencie);

    	$sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder', array('select'))
    	->disableOriginalConstructor()
    	->getMock();
    	$sourceFinderStub->expects($this->once())
    	->method('getAllAdapters')
    	->will($this->returnValue($metadataSourceCollection));


    	$sUT = new MetaDataSourcesQuestion($sourceFinderStub);
    	$this->assertEquals($source, $sUT->ask('My nameFactory'));
    }

    public function testWithPreselectedFail()
    {

        $metadataSourceCollection = new MetaDataSourceCollection();
        $source = new MetaDataSource();
        $source->setDefinition('My definition')
        ->setName('My name');
        $metadataSourceCollection->append($source);
        $sourceWithFailedDependencie = new MetaDataSource();
        $sourceWithFailedDependencie->setDefinition('My definition')
        ->setName('My name')
        ->setFalseDependencie('My false dependencies');
        $metadataSourceCollection->append($sourceWithFailedDependencie);

        $sourceFinderStub = $this->getMockBuilder('CrudGenerator\MetaData\MetaDataSourceFinder', array('select'))
        ->disableOriginalConstructor()
        ->getMock();
        $sourceFinderStub->expects($this->once())
        ->method('getAllAdapters')
        ->will($this->returnValue($metadataSourceCollection));


        $sUT = new MetaDataSourcesQuestion($sourceFinderStub);

        $this->setExpectedException('InvalidArgumentException');
        $sUT->ask('My name');
    }
}