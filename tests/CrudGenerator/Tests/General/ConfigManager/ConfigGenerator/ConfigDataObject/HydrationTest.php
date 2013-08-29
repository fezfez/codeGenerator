<?php
namespace CrudGenerator\Tests\General\ConfigManager\ConfigGenerator\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigGenerator\DataObject\ConfigDataObject;

class hydrationTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrationDTO()
    {
        $sUT = new ConfigDataObject();
        $sUT->setBaseNamespace('lorem');
        $sUT->setPathToModels('ipsum');
        $sUT->setMetadatasBackend('dolor');
        $sUT->setPathToMetadatas('si amet');

        $this->assertEquals(
            $sUT->getBaseNamespace(),
            'lorem'
        );
        $this->assertEquals(
            $sUT->getPathToModels(),
            'ipsum'
        );
        $this->assertEquals(
            $sUT->getMetadatasBackend(),
            'dolor'
        );
        $this->assertEquals(
            $sUT->getPathToMetadatas(),
            'si amet'
        );
    }
}
