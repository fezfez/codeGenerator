<?php
namespace CrudGenerator\Tests\General\ConfigManager\ConfigMetadata\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigMetadata\ManagerFactory;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testManagerOk()
    {
        chdir(__DIR__ .'/../../Data/');
        $sUT = new ManagerFactory;
        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigMetadata\Manager\YamlConfigMetadata',
            $sUT->create('Sample.yml')
        );
    }
}
