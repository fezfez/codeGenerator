<?php
namespace CrudGenerator\Tests\General\Adapater\AdapterDataObject;

use CrudGenerator\Adapter\AdapterDataObject;
use CrudGenerator\MetaData\PDO\PDOConfig;

class AdapterDataObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $pdoConfig = new PDOConfig();

        $adapater = new AdapterDataObject();

        $adapater->setConfig($pdoConfig)
                 ->setDefinition('definition')
                 ->setFalseDependencie('false')
                 ->setName('name');

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\PDO\PDOConfig',
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
