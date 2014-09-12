<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class ConfirmTest extends \PHPUnit_Framework_TestCase
{
    public function testNullResponse()
    {
        $request = new \Symfony\Component\HttpFoundation\Request();

        $sUT = new WebContext($request);

        $this->assertFalse($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringTrueResponse()
    {
        $request = new \Symfony\Component\HttpFoundation\Request(array('my_key' => 'true'));

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringFalseResponse()
    {
        $request = new \Symfony\Component\HttpFoundation\Request(array('my_key' => 'false'));

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringResponse()
    {
        $request = new \Symfony\Component\HttpFoundation\Request(array('my_key' => 'imstring'));

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringOneResponse()
    {
        $request = new \Symfony\Component\HttpFoundation\Request(array('my_key' => '1'));

        $sUT = new WebContext($request);

        $this->assertTrue($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }

    public function testStringZeroResponse()
    {
        $request = new \Symfony\Component\HttpFoundation\Request(array('my_key' => '0'));

        $sUT = new WebContext($request);

        $this->assertFalse($sUT->confirm('test', 'my_key'));
        $this->assertEquals('[]', json_encode($sUT));
    }
}
