<?php
namespace CrudGenerator\Tests\General\ConfigManager\ManagerFactory;

use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject;

class hydrationTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrationMainDTO()
    {

        $sUT = new YamlConfigDataObject();
        $sUT->setGenerators(array('lorem'))
            ->setName('ipsum')
            ->setOptions(array('dolor'))
            ->setPackageEnabled('si')
            ->setPackageName('amet')
            ->addOptions('et')
            ->addGeneratorsOptions('MyGenerator', 'MyQuestion', 'MyValue');

        $this->assertEquals(
            $sUT->getGenerators(),
            array('lorem')
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

        $this->assertEquals(
            $sUT->getGeneratorsOptions(),
            array(
                'MyGenerator' => array(
                    'MyQuestion' => 'MyValue'
                )
            )
        );
    }
}
