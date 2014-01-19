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
        $viewRenderer->getHelper('toto');
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Notice
     */
    public function testFailOnGetDataObject()
    {
        $viewRenderer = new ViewRenderer(array('FixtureRendererFactory' => '\CrudGenerator\View\Helpers\FixtureRendererFactory'));

        $this->assertInstanceOf('\CrudGenerator\View\Helpers\FixtureRenderer', $viewRenderer->getHelper('FixtureRenderer'));
    }

    public function testOk()
    {
        $viewRenderer = new ViewRenderer(array('FixtureRendererFactory' => '\CrudGenerator\View\Helpers\FixtureRendererFactory'));

        $viewRenderer->dataObject = $this->getMockForAbstractClass('CrudGenerator\DataObject');

        $this->assertInstanceOf('\CrudGenerator\View\Helpers\FixtureRenderer', $viewRenderer->getHelper('FixtureRenderer'));
    }
}
