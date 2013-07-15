<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinderFactory;

use CrudGenerator\Adapter\AdapterFinderFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Adapter\AdapterFinder',
            AdapterFinderFactory::getInstance()
        );
    }
}
