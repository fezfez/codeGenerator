<?php
namespace CrudGenerator\Tests\General\View\View;

use CrudGenerator\View\View;
use CrudGenerator\View\ViewRenderer;
use CrudGenerator\DataObject;

class GetHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $viewRenderer = new ViewRenderer(array());

        $this->setExpectedException('CrudGenerator\View\ViewRendererException');
        $viewRenderer->getHelper('toto', $this->getMockForAbstractClass('CrudGenerator\DataObject'));
    }

    public function testOk()
    {
        $viewRenderer = new ViewRenderer(array('FixtureRendererFactory' => '\CrudGenerator\View\Helpers\FixtureRendererFactory'));

        $this->assertInstanceOf('\CrudGenerator\View\Helpers\FixtureRenderer', $viewRenderer->getHelper('FixtureRenderer', $this->getMockForAbstractClass('CrudGenerator\DataObject')));
    }
}
