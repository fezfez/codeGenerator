<?php
namespace CrudGenerator\Tests\General\Adapater\MetaDataSource;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

class MetaDataSourceDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $PostgreSQLConfig = new PostgreSQLConfig();

        $adapater = new MetaDataSource();

        $adapater->setConfig($PostgreSQLConfig)
                 ->setDefinition('definition')
                 ->setFalseDependencie('false')
                 ->setMetaDataDAO('name')
                 ->setMetaDataDAOFactory('test');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig',
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
            $adapater->getMetaDataDAO()
        );

        $this->assertEquals(
            'test',
            $adapater->getMetaDataDAOFactory()
        );
    }
}
