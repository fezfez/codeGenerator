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
        $fileManager = new FileManager();
        $sUt = ZendFramework2Environnement::getDependence($fileManager);
        $sUt = ZendFramework2Environnement::getDependence($fileManager);
    }
}

