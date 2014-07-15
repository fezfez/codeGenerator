<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class LogTest extends \PHPUnit_Framework_TestCase
{
    public function testOkdd()
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

        $sUT->log('test', 'my_log');
        $sUT->log('test2', 'my_log');

        $this->assertEquals('{"my_log":["test","test2"]}', json_encode($sUT));
    }
}
