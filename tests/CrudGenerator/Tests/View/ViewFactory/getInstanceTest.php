<?php
namespace CrudGenerator\Tests\View\ViewFactory;

use CrudGenerator\View\ViewFactory;

class getInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\View\View',
             ViewFactory::getInstance()
        );
    }
}

