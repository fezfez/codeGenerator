<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class AskCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonWellReturned()
    {
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new WebContext($requestMock);

        $this->assertEquals(
            null,
            $sUT->askCollection('test', 'my_key', array('key' => 'value'))
        );
        $this->assertEquals(
            '{"question":[{"text":"test","dtoAttribute":"my_key","defaultResponse":null,"required":false,"values":{"key":"value"},"type":"select"}]}',
            json_encode($sUT)
        );
    }
}
