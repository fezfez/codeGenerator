<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonWellReturned()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new WebContext($request);

        $this->assertEquals(null, $sUT->ask('test', 'my_key'));
        $this->assertEquals(
            '{"question":[{"text":"test","dtoAttribute":"my_key","defaultResponse":null,"required":false,"type":"text"}]}',
            json_encode($sUT)
        );
    }
}
