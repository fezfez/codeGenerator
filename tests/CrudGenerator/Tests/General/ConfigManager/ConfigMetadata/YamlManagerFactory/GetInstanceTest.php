<?php
namespace CrudGenerator\Tests\General\ConfigManager\ConfigMetadata\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigMetadata\ManagerFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testManagerOk()
    {
        chdir(__DIR__ .'/../../Data/');
        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigMetadata\Manager\YamlConfigMetadata',
            ManagerFactory::getInstance('Sample.yml')
        );
    }

    public function testManagerKo()
    {
        chdir(__DIR__ .'/../../Data/');
        $this->setExpectedException(
                'CrudGenerator\ConfigManager\ConfigMetadata\InvalidYamlConfigPathException'
        );
        ManagerFactory::getInstance('toto/');
    }

    public function testManagerGetMetadata()
    {
        chdir(__DIR__ .'/../../Data/');

        $manager = ManagerFactory::getInstance('Sample.yml');

        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject',
            $manager->getMetadatas()
        );
    }
}
