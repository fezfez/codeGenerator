<?php
namespace CrudGenerator\Tests\General\View\Helpers\FixtureRendererFactory;

use CrudGenerator\View\Helpers\FixtureRendererFactory;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use Faker\Factory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\View\Helpers\FixtureRenderer',
            FixtureRendererFactory::getInstance($this->getMockForAbstractClass('CrudGenerator\DataObject'))
        );
    }
}
