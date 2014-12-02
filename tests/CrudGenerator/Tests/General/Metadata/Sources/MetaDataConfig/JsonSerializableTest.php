<?php

namespace CrudGenerator\Tests\General\Metadata\Sources\MetaDataConfig;

class JsonSerializableTest extends \PHPUnit_Framework_TestCase
{
    public function testHaveData()
    {
        $classes = new \CrudGenerator\Utils\ClassAwake();

        $implementations = $classes->wakeByInterfaces(
            array(getcwd().'/src'),
            'CrudGenerator\Metadata\Sources\MetaDataConfig'
        );

        foreach ($implementations as $class) {
            /* @var $object \CrudGenerator\Metadata\Sources\MetaDataConfig */
            $object = new $class();

            $this->assertRegExp(
                '/metadataDaoFactory/',
                json_encode($object),
                sprintf('class "%s" does not have property "metaDataDAOFactory"', $class)
            );
        }
    }

    public function testcanBeRehydrated()
    {
        $classes = new \CrudGenerator\Utils\ClassAwake();

        $implementations = $classes->wakeByInterfaces(
            array(getcwd().'/src'),
            'CrudGenerator\Metadata\Sources\MetaDataConfig'
        );

        $getterWithoutSetter = array('uniqueName', 'metaDataDAOFactory');

        foreach ($implementations as $class) {
            /* @var $object \CrudGenerator\Metadata\Sources\MetaDataConfig */
            $object = new $class();

            $jsonObject    = json_encode($object);
            $methodsObject = get_class_methods($object);
            $jsonDecoded   = json_decode($jsonObject);

            foreach ($jsonDecoded as $configAttr => $configValue) {
                if (in_array($configAttr, $getterWithoutSetter) === true) {
                    continue;
                }

                $method = 'set'.ucfirst($configAttr);
                $this->assertTrue(
                    in_array($method, $methodsObject),
                    sprintf('"%s" does not have the method "%s" to re hydrate "%s"', $class, $method, $configAttr)
                );
            }
        }
    }
}
