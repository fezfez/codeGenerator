<?php
namespace CrudGenerator\Tests\General\View\Helpers\TemplateDatabaseConnectorStrategies\ZendFramework2Strategy;

use CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\ZendFramework2Strategy;
use ReflectionClass;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {
        $class = new ReflectionClass('CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\ZendFramework2Strategy');
        $methods = $class->getMethods();
        $dataObject = $this->getMockForAbstractClass('CrudGenerator\DataObject');
        $dataObject->setEntity('MyEntity');

        $sUT = new ZendFramework2Strategy;
        foreach ($methods as $method) {
            $methodName = $method->name;
            $this->assertInternalType(
                'string',
                $sUT->$methodName($dataObject)
            );
        }
    }
}
