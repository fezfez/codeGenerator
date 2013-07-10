<?php
namespace CrudGenerator\Tests\MetaData\Config\MetaDataConfigReader;

use CrudGenerator\MetaData\Config\MetaDataConfigReader;
use CrudGenerator\FileManager;
use CrudGenerator\MetaData\PDO\PDOConfig;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\DialogHelper;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubConsole = $this->getMock('\Symfony\Component\Console\Output\ConsoleOutput');
        $stubConsole->expects($this->any())
                    ->method('writeln')
                    ->will($this->returnValue('foo'));

        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
                   ->method('ask')
                   ->will($this->returnValue('foo'));

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->once())
                        ->method('filePutsContent')
                        ->will($this->returnValue(true));


        $suT = new MetaDataConfigReader(
            $stubConsole,
            $stubDialog,
            $stubFileManager
        );

        $stubConfig = $this->getMock('\CrudGenerator\MetaData\PDO\PDOConfig');

        $stubConfig->expects($this->once())
                   ->method('test')
                   ->with($this->equalTo($stubConsole))
                   ->will($this->returnValue(false));

        $stubConfig->expects($this->once())
                   ->method('test')
                   ->with($this->equalTo($stubConsole))
                   ->will($this->returnValue(true));

        $suT->config($stubConfig);
    }
}

