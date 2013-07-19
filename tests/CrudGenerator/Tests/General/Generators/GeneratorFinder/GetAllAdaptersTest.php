<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorFinder;

use CrudGenerator\Generators\GeneratorFinder;
use CrudGenerator\FileManager;

class GetAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $fileManager = new FileManager();

        $suT = new GeneratorFinder($fileManager);

        $this->assertInternalType(
            'array',
            $suT->getAllClasses()
        );
    }


    public function testFail()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(true));

        $config = include __DIR__ . '/../../../ZF2/config/application.config.php';
        $config['crudGenerator']['path'] = array(
            __DIR__,
            __DIR__ . '/FZEAFAZ/'
        );
        $stubFileManager->expects($this->any())
                        ->method('includeFile')
                        ->will($this->returnValue($config));

        $suT = new GeneratorFinder($stubFileManager);

        $this->setExpectedException('RuntimeException');


        $suT->getAllClasses();
    }

    /*public function testFailOnGetConfig()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('fileExists')
                        ->will($this->returnValue(false));

        $suT = new GeneratorFinder($stubFileManager);

        $this->setExpectedException('RuntimeException');

        $suT->getAllClasses();
    }*/
}
