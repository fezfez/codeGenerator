<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorFinderFactory;
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\History\History;

class FindAllTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
                        ->method('isDir')
                        ->will($this->returnValue(false));

        $this->setExpectedException('CrudGenerator\EnvironnementResolver\EnvironnementResolverException');

        $sUT = new HistoryManager($stubFileManager);

        $sUT->findAll();
    }

    public function testFind()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->any())
        ->method('isDir')
        ->will($this->returnValue(true));


        $glob = array(
            serialize(new History())
        );

        $stubFileManager->expects($this->any())
                        ->method('glob')
                        ->will($this->returnValue($glob));

        $sUT = new HistoryManager($stubFileManager);

        $sUT->findAll();
    }
}
