<?php
namespace CrudGenerator\Tests\General\EnvironnementResolver;

use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\FileManager;

/**
 * @runTestsInSeparateProcesses
 */
class ZendFramework2EnvironnementTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue(include __DIR__ . '/../../ZF2/config/application.config.php'));

        $sUt = ZendFramework2Environnement::getDependence($stubFileManager);
        $sUt = ZendFramework2Environnement::getDependence($stubFileManager);
    }
}
