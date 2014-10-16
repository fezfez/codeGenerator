<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigDAO;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use phpDocumentor\Reflection\DocBlock;

class RetrieveAllTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $context = $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $fileManager->expects($this->once())
        ->method('glob')
        ->will($this->returnValue(array('myFile')));

        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(json_encode(array('key' => 'val', 'metadataDaoFactory' => 'nonexistMetadata'))));

        $sUT = new MetaDataConfigDAO(
            new ClassAwake(),
            $fileManager,
            new MetaDataSourceHydrator(),
            $context
        );

        $results = $sUT->retrieveAll();

        $this->assertInstanceOf('CrudGenerator\MetaData\MetaDataSourceCollection', $results);
    }
}
