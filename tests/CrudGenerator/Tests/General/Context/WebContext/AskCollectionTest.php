<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class AskCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT = new WebContext($application);

        $this->assertEquals(null, $sUT->askCollection('test', 'my_key', array('key' => 'value')));
        $this->assertEquals('{"question":[{"text":"test","dtoAttribute":"my_key","defaultResponse":null,"required":false,"values":{"key":"value"},"type":"select"}]}', json_encode($sUT));
    }
}
