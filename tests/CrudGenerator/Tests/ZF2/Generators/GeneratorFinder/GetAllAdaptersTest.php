<?php
namespace CrudGenerator\Tests\ZF2\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        chdir(__DIR__);
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
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

        $suT = new GeneratorFinder($stubFileManager, new ClassAwake());

        $this->setExpectedException('RuntimeException');
        $suT->getAllClasses();
    }

    public function testType()
    {
        chdir(__DIR__);
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager, new ClassAwake());

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }
}
