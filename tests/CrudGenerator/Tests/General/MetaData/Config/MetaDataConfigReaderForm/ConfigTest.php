<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderForm;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderForm;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testFirstConfigOk()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->once())
                        ->method('filePutsContent')
                        ->will($this->returnValue(true));
        $stubConfig = $this->getMock('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig');

        $stubFileManager->expects($this->once())
        ->method('fileGetContent')
        ->will($this->returnValue(serialize($stubConfig)));
        $stubFileManager->expects($this->once())
        ->method('isFile')
        ->will($this->returnValue(true));


        $suT = new MetaDataConfigReaderForm(
            $stubFileManager
        );

        $stubConfig = $this->getMock('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig');

        $suT->config($stubConfig);
    }

    public function testWithFailConfig()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');
        $stubFileManager->expects($this->once())
                        ->method('isFile')
                        ->will($this->returnValue(false));

        $suT = new MetaDataConfigReaderForm(
            $stubFileManager
        );

        $stubConfig = $this->getMock('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig');

		$this->setExpectedException('CrudGenerator\MetaData\Config\ConfigException');
        $suT->config($stubConfig);
    }
}
