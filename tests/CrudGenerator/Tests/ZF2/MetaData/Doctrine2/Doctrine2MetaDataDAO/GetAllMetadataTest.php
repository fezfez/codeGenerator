<?php
namespace CrudGenerator\Tests\ZF2\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;

use CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\Utils\FileManager;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue(include __DIR__ . '/../../../config/application.config.php'));

        $sm = ZendFramework2Environnement::getDependence($stubFileManager);
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $sUT = new Doctrine2MetaDataDAO($em);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\DataObject\MetaDataCollection',
            $sUT->getAllMetadata()
        );
    }
}
