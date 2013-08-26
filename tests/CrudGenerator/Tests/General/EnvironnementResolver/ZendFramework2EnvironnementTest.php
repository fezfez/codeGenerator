<?php
namespace CrudGenerator\Tests\General\EnvironnementResolver;

use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\FileManager;

class ZendFramework2EnvironnementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     */
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

    /**
     * @runInSeparateProcess
     */
    public function testWrongZf2Config()
    {

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
        ->method('fileExists')
        ->will($this->returnValue(true));

        $stubFileManager->expects($this->any())
        ->method('includeFile')
        ->will($this->returnValue(array(
            'modules' => array(
                'MyWrongModule'
            ),
            'module_listener_options' => array(
                'module_paths' => array(
                    './../../../../../vendor',
                    './../module',
                ),
                'config_glob_paths' => array(
                    'config/autoload/{,*.}{global,local}.php',
                ),
            )
        )));

        $this->setExpectedException('CrudGenerator\EnvironnementResolver\EnvironnementResolverException');
        $sUt = ZendFramework2Environnement::getDependence($stubFileManager);
    }
}
