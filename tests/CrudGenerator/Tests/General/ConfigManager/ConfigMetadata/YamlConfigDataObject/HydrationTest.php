<?php
namespace CrudGenerator\Tests\General\ConfigManager\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\Property;

use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\PropertiesCollection;

use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject;

class hydrationTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrationMainDTO()
    {
        $propertiesCollection = new PropertiesCollection();
        $property = new Property();

        $sUT = new YamlConfigDataObject();
        $sUT->setGenerators('lorem')
            ->setName('ipsum')
            ->setOptions(array('dolor'))
            ->setPackageEnabled('si')
            ->setPackageName('amet')
            ->setPropertiesCollection($propertiesCollection)
            ->addOptions('et')
            ->addPropertiesCollection($property);
        $this->assertEquals(
            $sUT->getGenerators(),
            'lorem'
        );
        $this->assertEquals(
            $sUT->getName(),
            'ipsum'
        );
        $this->assertEquals(
            $sUT->getOptions(),
            array('dolor', 'et')
        );
        $this->assertEquals(
            $sUT->getPackageEnabled(),
            'si'
        );
        $this->assertEquals(
            $sUT->getPackageName(),
            'amet'
        );
        $this->assertInstanceOf(
            'CrudGenerator\ConfigManager\ConfigMetadata\DataObject\PropertiesCollection',
            $sUT->getPropertiesCollection()
        );
    }

    public function testHydrationPropertyDTO()
    {
        $sUT = new Property();
        $sUT->setName('name')
            ->setOptions(array('option1'))
            ->addOptions('option2')
            ->setType('type');

        $this->assertEquals(
                $sUT->getOptions(),
                array('option1', 'option2')
        );
        $this->assertEquals(
                $sUT->getname(),
                'name'
        );
        $this->assertEquals(
                $sUT->getType(),
                'type'
        );
    }
}
