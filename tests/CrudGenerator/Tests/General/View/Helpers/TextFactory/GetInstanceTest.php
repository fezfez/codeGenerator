<?php
namespace CrudGenerator\Tests\General\View\Helpers\TextFactory;

use CrudGenerator\View\Helpers\TextFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\View\Helpers\Text',
            TextFactory::getInstance()
        );
    }
}
