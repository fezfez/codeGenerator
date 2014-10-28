<?php
namespace CrudGenerator\Tests\General\Context\PredefinedResponse;

use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Tests\TestCase;

class GetterSetterTest extends TestCase
{
    public function testString()
    {
        $sUT = new PredefinedResponse('id', 'label', 'response');

        $this->assertEquals(array(), $sUT->getAdditionalData());
        $this->assertEquals('id', $sUT->getId());
        $this->assertEquals('label', $sUT->getLabel());
        $this->assertEquals('response', $sUT->getResponse());

        $sUT->setId('test');
        $sUT->setLabel('toto');
        $sUT->setResponse('test');
        $sUT->setAdditionalData(array('fez'));

        $this->assertEquals('test', $sUT->getId());
        $this->assertEquals('toto', $sUT->getLabel());
        $this->assertEquals('test', $sUT->getResponse());
        $this->assertEquals(array('fez'), $sUT->getAdditionalData());
    }
}
