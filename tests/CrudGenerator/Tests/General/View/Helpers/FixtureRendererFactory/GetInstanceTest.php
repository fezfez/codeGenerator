<?php
namespace CrudGenerator\Tests\General\View\Helpers\FixtureRendererFactory;

use CrudGenerator\View\Helpers\FixtureRendererFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\View\Helpers\FixtureRenderer',
            FixtureRendererFactory::getInstance()
        );
    }
}
