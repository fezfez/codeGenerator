<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\History\History;
use CrudGenerator\History\HistoryManager;
use CrudGenerator\Tests\TestCase;

class FindAllTest extends TestCase
{
    public function testFail()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubHistoryHydrator = $this->createMock('CrudGenerator\History\HistoryHydrator');
        $stubFileManager     = $this->createMock('CrudGenerator\Utils\FileManager');

        $stubFileManager->expects($this->any())
                        ->method('isDir')
                        ->will($this->returnValue(false));

        $this->setExpectedException('CrudGenerator\EnvironnementResolver\EnvironnementResolverException');

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $sUT->findAll();
    }

    public function testFind()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubHistoryHydrator = $this->createMock('CrudGenerator\History\HistoryHydrator');
        $stubFileManager     = $this->createMock('CrudGenerator\Utils\FileManager');

        $stubFileManager->expects($this->any())
        ->method('isDir')
        ->will($this->returnValue(true));

        $glob = array(
            serialize(new History()),
        );

        $stubFileManager->expects($this->any())
                        ->method('glob')
                        ->will($this->returnValue($glob));

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $this->assertInstanceOf('CrudGenerator\History\HistoryCollection', $sUT->findAll());
    }
}
