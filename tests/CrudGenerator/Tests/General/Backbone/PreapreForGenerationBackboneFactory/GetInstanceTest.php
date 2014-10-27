<?php
namespace CrudGenerator\Tests\General\Backbone\PreapreForGenerationBackboneFactory;

use CrudGenerator\Backbone\PreapreForGenerationBackboneFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $contextStub = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\Backbone\PreapreForGenerationBackbone',
            PreapreForGenerationBackboneFactory::getInstance($contextStub)
        );
    }
}
