<?php
namespace CrudGenerator\Tests\General\Adapater\MetaDataSource;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

class MetaDataSourceDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = new PDOConfig();

        $adapater = new MetaDataSource();

        $adapater->setConfig($pdoConfig)
                 ->setDefinition('definition')
                 ->setFalseDependencie('false')
                 ->setName('name');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PDO\PDOConfig',
            $adapater->getConfig()
        );

        $this->assertEquals(
            'definition',
            $adapater->getDefinition()
        );

        $this->assertEquals(
            'false',
            $adapater->getFalseDependencies()
        );

        $this->assertEquals(
            'name',
            $adapater->getName()
        );

        $this->assertEquals(
            'nameFactory',
            $adapater->getFactory()
        );
    }
}
