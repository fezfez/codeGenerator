<?php

namespace CrudGenerator\Tests\General\MetaData\Sources\MetaDataConfig;

class JsonSerializableTest extends \PHPUnit_Framework_TestCase
{
    public function testHaveData()
    {
        $classes = new \CrudGenerator\Utils\ClassAwake();
        $implementations = $classes->wakeByInterfaces(array(getcwd() . '/src'), 'CrudGenerator\MetaData\Sources\MetaDataConfig');


        foreach ($implementations as $class) {
            /* @var $object \CrudGenerator\MetaData\Sources\MetaDataConfig */
            $object = new $class();
            $object->setMetaDataDAOFactory('testets');

            $this->assertRegExp(
            	'/metaDataDAOFactory/',
            	json_encode($object),
            	sprintf('class "%s" does not have property "metaDataDAOFactory"', $class)
        	);
        }
    }
}