<?php
namespace CrudGenerator\Tests\General\View\Helpers\TemplateServiceContainer;

use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\PDOStrategy;
use CrudGenerator\View\Helpers\TemplateServiceContainer;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {

        $sUT = new TemplateServiceContainer(new PDOStrategy());

        $this->assertInternalType(
            'string',
            $sUT->getClassName()
        );

        $this->assertInternalType(
            'string',
            $sUT->getFullClass()
        );

        $this->assertInternalType(
            'string',
            $sUT->getInjectionInDependencie()
        );

        $this->assertInternalType(
            'string',
            $sUT->getVariableName()
        );

        $this->assertInternalType(
            'string',
            $sUT->getCreateInstanceForUnitTest()
        );

        $this->assertInternalType(
            'string',
            $sUT->getFullClassForUnitTest()
        );
    }
}
