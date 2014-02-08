<?php
namespace CrudGenerator\Tests\General\Generators\Questions\Cli\MetaDataSourcesQuestion;


use CrudGenerator\Generators\Questions\Cli\MetaDataSourcesQuestion;
use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\MetaData\MetaDataSource;


class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $dialog->expects($this->once())
        ->method('select')
        ->will($this->returnValue(0));

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


        $sUT = new MetaDataSourcesQuestion($sourceFinderStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($source, $sUT->ask());
    }

    public function testWithPreselected()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $dialog->expects($this->never())
        ->method('select');

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


        $sUT = new MetaDataSourcesQuestion($sourceFinderStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($source, $sUT->ask('My name'));
    }
}