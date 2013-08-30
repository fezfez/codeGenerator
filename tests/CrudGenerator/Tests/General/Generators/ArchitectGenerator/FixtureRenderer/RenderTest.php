<?php
namespace CrudGenerator\Tests\General\Generators\ArchitectGenerator\FixtureRenderer;

use CrudGenerator\Generators\ArchitectGenerator\FixtureRenderer;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedzdzaz()
    {
        $sUT = new FixtureRenderer();
        $metadata = new MetaDataColumn();

        $this->assertInternalType(
            'integer',
            $sUT->render($metadata->setType('integer'))
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
