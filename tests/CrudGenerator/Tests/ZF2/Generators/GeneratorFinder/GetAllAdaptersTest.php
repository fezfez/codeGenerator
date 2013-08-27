<?php
namespace CrudGenerator\Tests\ZF2\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\FileManager;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        chdir(__DIR__);
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager);

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }

    public function testFaifezfel()
    {
        chdir(__DIR__);
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->once())
                        ->method('fileExists')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->once())
        ->method('includeFile')
        ->will($this->returnValue(array(
            'modules' => array(
                'DoctrineModule',
                'DoctrineORMModule',
                'WrongTestZf2'
            ),
            'module_listener_options' => array(
                'module_paths' => array(
                    './../../../../../vendor',
                    './../../wrongModule',
                ),
                'config_glob_paths' => array(
                    'config/autoload/{,*.}{global,local}.php',
                ),
            )
        )));

        $suT = new GeneratorFinder($stubFileManager);

        $this->setExpectedException('RuntimeException');
        $suT->getAllClasses();
    }
}