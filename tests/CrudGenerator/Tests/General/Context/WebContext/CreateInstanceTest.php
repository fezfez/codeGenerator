<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class CreateInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($request));

        $sUT = new WebContext($application);
    }
}
