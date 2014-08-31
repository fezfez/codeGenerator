<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class ConfirmTest extends \PHPUnit_Framework_TestCase
{
    public function testNullResponse()
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

        $this->assertFalse($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringTrueResponse()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('true');

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT = new WebContext($application);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringFalseResponse()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('false');

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT = new WebContext($application);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringResponse()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('imstring');

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT = new WebContext($application);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringOneResponse()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('1');

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT = new WebContext($application);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringZeroResponse()
    {
        $application = $this->getMockBuilder('Silex\Application')
        ->disableOriginalConstructor()
        ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('0');

        $myTmpObject = new \ArrayObject();
        $myTmpObject->request = $request;

        $application->expects($this->once())
        ->method('offsetGet')
        ->with('request')
        ->will($this->returnValue($myTmpObject));

        $sUT = new WebContext($application);

        $this->assertFalse($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }
}
