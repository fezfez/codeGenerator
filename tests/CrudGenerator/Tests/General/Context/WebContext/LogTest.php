<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class LogTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new WebContext($request);

        $sUT->log('test', 'my_log');
        $sUT->log('test2', 'my_log');

        $this->assertEquals('{"my_log":["test","test2"]}', json_encode($sUT));
    }
}
