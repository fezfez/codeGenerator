<?php
namespace CrudGenerator\Tests\General\View\View;

use CrudGenerator\View\View;
use CrudGenerator\View\ViewRenderer;
use CrudGenerator\Utils\FileManager;

class GetHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testFail()
    {
        $viewRenderer = new ViewRenderer(array());

        $this->setExpectedException('CrudGenerator\View\ViewRendererException');
        $viewRenderer->getHelper('toto');
    }

    public function testOk()
    {
        $viewRenderer = new ViewRenderer(array('FixtureRenderer' => '\CrudGenerator\Generators\ArchitectGenerator\FixtureRenderer'));

        $this->assertInstanceOf('\CrudGenerator\Generators\ArchitectGenerator\FixtureRenderer', $viewRenderer->getHelper('FixtureRenderer'));
    }
}
