<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderForm;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderForm;

class WriteTest extends \PHPUnit_Framework_TestCase
{
    public function testReturn()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');

        $suT = new MetaDataConfigReaderForm($stubFileManager);

        $stubConfig = new \CrudGenerator\MetaData\Sources\PDO\PDOConfig();

        $this->assertEquals(
        	'test',
        	$suT->write($stubConfig, array('databaseName' => 'test'))->getDatabaseName()
		);
    }
}
