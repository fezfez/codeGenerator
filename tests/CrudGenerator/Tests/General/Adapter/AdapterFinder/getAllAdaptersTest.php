<?php
namespace CrudGenerator\Tests\General\Adapater\AdapaterFinder;

use CrudGenerator\Adapter\AdapterFinder;

class getAllAdapatersTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $suT = new AdapterFinder();
        $this->assertInstanceOf(
            'CrudGenerator\Adapter\AdapterCollection',
             $suT->getAllAdapters()
        );
    }
}

