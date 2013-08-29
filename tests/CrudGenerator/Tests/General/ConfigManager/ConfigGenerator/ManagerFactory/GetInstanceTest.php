<?php
namespace CrudGenerator\Tests\General\ConfigGenerator\ConfigManager\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigGenerator\ManagerFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testManagerOk()
    {
        chdir(__DIR__ .'/../../Data/');
        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator',
            ManagerFactory::getInstance('')
        );
    }

    public function testManagerKo()
    {
        chdir(__DIR__ .'/../../Data/');
        $this->setExpectedException(
                'CrudGenerator\ConfigManager\ConfigGenerator\InvalidConfigPathException'
        );
        ManagerFactory::getInstance('toto/');
    }

    public function testManagerGetConfig()
    {
        chdir(__DIR__ .'/../../Data/');

        $manager = ManagerFactory::getInstance('');

        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigGenerator\DataObject\ConfigDataObject',
            $manager->getConfig()
        );
    }
}
