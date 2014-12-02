<?php
namespace CrudGenerator\Tests\General\View\View;

use CrudGenerator\View\View;
use CrudGenerator\View\ViewRenderer;

class RenderFileTest extends \PHPUnit_Framework_TestCase
{
    public function testReturn()
    {
        $SUT = new ViewRenderer(array());

        $this->assertEquals(
            'test',
            $SUT->renderFile(
                __DIR__.'/../viewTemplateStatic.phtml'
            )
        );
    }

    public function testFail()
    {
        $SUT = new ViewRenderer(array());

        $this->setExpectedException('CrudGenerator\View\ViewRendererException');

        $SUT->renderFile(
            __DIR__.'/../viewTemplate.phtml'
        );
    }
}
