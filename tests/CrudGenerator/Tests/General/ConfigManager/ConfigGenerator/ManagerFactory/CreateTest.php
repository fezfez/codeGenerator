<?php
namespace CrudGenerator\Tests\General\ConfigGenerator\ConfigManager\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigGenerator\ManagerFactory;

class CreateTest extends \PHPUnit_Framework_TestCase
{
    public function testManagerOk()
    {
        $sUT = new ManagerFactory();

        chdir(__DIR__ .'/../../Data/');
        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator',
            $sUT->create('')
        );
    }
}
