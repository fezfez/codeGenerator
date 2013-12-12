<?php
namespace CrudGenerator\Tests\General\History\HistoryManager;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorFinderFactory;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class FindTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        // wakeup classes
        $generatorFinder = GeneratorFinderFactory::getInstance();
        $generatorFinder->getAllClasses();

        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubHistoryHydrator = $this->getMockBuilder('\CrudGenerator\History\HistoryHydrator')
        ->disableOriginalConstructor()
        ->getMock();

        $stubHistoryHydrator->expects($this->once())
        				    ->method('yamlToDTO');

        $historyName = 'toto';
        $stubFileManager->expects($this->once())
                        ->method('isFile')
                        ->with(HistoryManager::HISTORY_PATH . $historyName . '.history.yaml')
                        ->will($this->returnValue(true));

        $sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

        $sUT->find($historyName);
    }

    public function testError()
    {
    	// wakeup classes
    	$generatorFinder = GeneratorFinderFactory::getInstance();
    	$generatorFinder->getAllClasses();

    	$stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
    	$stubHistoryHydrator = $this->getMockBuilder('\CrudGenerator\History\HistoryHydrator')
    	->disableOriginalConstructor()
    	->getMock();

    	$historyName = 'toto';
    	$stubFileManager->expects($this->once())
    	->method('isFile')
    	->with(HistoryManager::HISTORY_PATH . $historyName . '.history.yaml')
    	->will($this->returnValue(false));

    	$sUT = new HistoryManager($stubFileManager, $stubHistoryHydrator);

    	$this->setExpectedException('CrudGenerator\History\HistoryNotFoundException');

    	$sUT->find($historyName);
    }
}
