<?php
namespace CrudGenerator\Tests\General\View\Helpers\FixtureRenderer;

use CrudGenerator\View\Helpers\FixtureRenderer;
use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use Faker\Factory;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {
        $sUT      = new FixtureRenderer(Factory::create());
        $metadata = new MetaDataColumn();

        $this->assertInternalType(
            'integer',
            $sUT->render($metadata->setType('integer'))
        );

        $this->assertInternalType(
            'string',
            $sUT->render($metadata->setType('string'))
        );

        $metadata->setLength(2);
        $this->assertInternalType(
            'string',
            $sUT->render($metadata->setType('string'))
        );
        $metadata->setLength(10);
        $this->assertInternalType(
            'string',
            $sUT->render($metadata->setType('string'))
        );

        $this->assertInternalType(
            'string',
            $sUT->render($metadata->setType('date'))
        );

        $this->assertInternalType(
            'string',
            $sUT->render($metadata->setType('bool'))
        );
    }
}
