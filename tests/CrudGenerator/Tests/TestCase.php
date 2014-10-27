<?php
namespace CrudGenerator\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $className
     * @return array
     */
    public function createSut($className)
    {
        $class = new \ReflectionClass($className);
        $mocks = array();

        foreach ($class->getConstructor()->getParameters() as $param) {
            $mocks[$param->name] = $this->createMock($param->getClass()->name);
        }

        return array(
            'instance' => function($mocks) use ($class) { return $class->newInstanceArgs($mocks); },
            'mocks'    => $mocks
        );
    }

    /**
     * @param string $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createMock($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}