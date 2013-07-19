<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\FileManager;
use CrudGenerator\Generators\GeneratorFinderFactory;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class FindAllTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('isDir')
                        ->will($this->returnValue(false));
        $stubFileManager->expects($this->any())
                        ->method('isDir')
                        ->will($this->returnValue(true));

        $sUT = new HistoryManager($stubFileManager);

        $sUT->findAll();
    }
}
