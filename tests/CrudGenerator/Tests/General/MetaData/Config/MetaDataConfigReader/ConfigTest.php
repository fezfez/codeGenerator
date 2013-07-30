<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReader;

use CrudGenerator\MetaData\Config\MetaDataConfigReader;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testFirstConfigOk()
    {
        $stubConsole =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubConsole->expects($this->any())
                    ->method('writeln')
                    ->will($this->returnValue('foo'));

        $stubDialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubDialog->expects($this->any())
                   ->method('ask')
                   ->will($this->returnValue('foo'));

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');
        $stubFileManager->expects($this->once())
                        ->method('filePutsContent')
                        ->will($this->returnValue(true));
        $stubFileManager->expects($this->once())
                        ->method('unlink')
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
                   ->will($this->returnValue(true));

        $suT->config($stubConfig);
    }

    public function testWithFailConfig()
    {
        $stubConsole =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubConsole->expects($this->any())
                    ->method('writeln')
                    ->will($this->returnValue('foo'));

        $stubDialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
                            ->disableOriginalConstructor()
                            ->getMock();
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

        $stubConfig->expects($this->at(0))
                   ->method('test')
                   ->with($this->equalTo($stubConsole))
                   ->will($this->throwException(new \CrudGenerator\MetaData\Config\ConfigException()));

        $stubConfig->expects($this->at(1))
                   ->method('test')
                   ->with($this->equalTo($stubConsole))
                   ->will($this->throwException(new \CrudGenerator\MetaData\Config\ConfigException()));

         $stubConfig->expects($this->at(2))
                    ->method('test')
                    ->with($this->equalTo($stubConsole))
                    ->will($this->throwException(new \CrudGenerator\MetaData\Config\ConfigException()));

        $suT->config($stubConfig);
    }

    public function testWithExistingConfigaaa()
    {
        $stubConsole =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubConsole->expects($this->any())
                    ->method('writeln')
                    ->will($this->returnValue('foo'));

        $stubDialog =  $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
                            ->disableOriginalConstructor()
                            ->getMock();
        $stubDialog->expects($this->any())
                   ->method('ask')
                   ->will($this->returnValue('foo'));

        $stubConfig = $this->getMock('\CrudGenerator\MetaData\PDO\PDOConfig');

        /*Ne peut pas étre testéjusquau bout car la désérialization alter le mock
        $stubConfig->expects($this->once())
                   ->method('test')
                   ->will($this->returnValue(true));*/

        $stubFileManager = $this->getMock('\CrudGenerator\FileManager');

        $stubFileManager->expects($this->once())
                        ->method('isFile')
                        ->will($this->returnValue(true));

        $stubFileManager->expects($this->once())
                        ->method('fileGetContent')
                        ->will($this->returnValue(serialize($stubConfig)));


        $suT = new MetaDataConfigReader(
            $stubConsole,
            $stubDialog,
            $stubFileManager
        );

        $suT->config($stubConfig);
    }
}
