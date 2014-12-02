<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\History\HistoryManager;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Utils\Installer;

class FindTest extends TestCase
{
    public function testOk()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager     = $this->createMock('\CrudGenerator\Utils\FileManager');
        $stubHistoryHydrator = $this->createMock('\CrudGenerator\History\HistoryHydrator');

        $stubHistoryHydrator->expects($this->once())
                            ->method('jsonToDTO');

        $historyName = 'toto';
        $stubFileManager->expects($this->once())
                        ->method('isFile')
                        ->with(Installer::BASE_PATH . HistoryManager::HISTORY_PATH . $historyName . '.history.yaml')
                        ->will($this->returnValue(true));

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $sUT->find($historyName);
    }

    public function testError()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager     = $this->createMock('\CrudGenerator\Utils\FileManager');
        $stubHistoryHydrator = $this->createMock('\CrudGenerator\History\HistoryHydrator');

        $historyName = 'toto';
        $stubFileManager->expects($this->once())
        ->method('isFile')
        ->with(Installer::BASE_PATH . HistoryManager::HISTORY_PATH . $historyName . '.history.yaml')
        ->will($this->returnValue(false));

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $this->setExpectedException('CrudGenerator\History\HistoryNotFoundException');

        $sUT->find($historyName);
    }
}
