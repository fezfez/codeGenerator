<?php
namespace CrudGenerator\Tests\General\View\View;

use CrudGenerator\View\View;
use CrudGenerator\View\ViewRenderer;
use CrudGenerator\Utils\FileManager;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    public function testReturn()
    {
        $viewRenderer = new ViewRenderer(array());

        $SUT = new View($viewRenderer);

        $this->assertEquals(
            'myValue',
            $SUT->render(
                __DIR__ . '/../',
                'viewTemplate.phtml',
                array('myVar' => 'myValue')
            )
        );
    }

    public function testFail()
    {
        $viewRenderer = new ViewRenderer(array());

        $SUT = new View($viewRenderer);

        $this->setExpectedException('CrudGenerator\View\ViewRendererException');

        $SUT->render(
            __DIR__ . '/../',
            'viewTemplate.phtml',
            array('undefined' => 'myValue')
        );
    }
}
