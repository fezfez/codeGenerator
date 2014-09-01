<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class ConfirmTest extends \PHPUnit_Framework_TestCase
{
    public function testNullResponse()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new WebContext($request);

        $this->assertFalse($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringTrueResponse()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('true');

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringFalseResponse()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('false');

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringResponse()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('imstring');

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringOneResponse()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('1');

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringZeroResponse()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $request->expects($this->once())
        ->method('get')
        ->with('my_key')
        ->willReturn('0');

        $sUT = new WebContext($request);

        $this->assertFalse($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }
}
