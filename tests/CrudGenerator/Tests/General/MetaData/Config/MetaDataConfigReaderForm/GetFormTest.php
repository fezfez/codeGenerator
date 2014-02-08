<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigReaderForm;

use CrudGenerator\MetaData\Config\MetaDataConfigReaderForm;

class GetFormTest extends \PHPUnit_Framework_TestCase
{
    public function testReturn()
    {
        $stubFileManager = $this->getMock('\CrudGenerator\Utils\FileManager');

        $suT = new MetaDataConfigReaderForm($stubFileManager);

        $stubConfig = $this->getMock('\CrudGenerator\MetaData\Sources\PDO\PDOConfig');

        $this->assertEquals(
        	array(
        		array('text' => 'databaseName', 'dtoAttribute' => 'databaseName'),
        		array('text' => 'host', 'dtoAttribute' => 'host'),
        		array('text' => 'user', 'dtoAttribute' => 'user'),
        		array('text' => 'password', 'dtoAttribute' => 'password'),
        		array('text' => 'port', 'dtoAttribute' => 'port'),
        		array('text' => 'type', 'dtoAttribute' => 'type'),
        	),
        	$suT->getForm($stubConfig)
		);
    }
}
