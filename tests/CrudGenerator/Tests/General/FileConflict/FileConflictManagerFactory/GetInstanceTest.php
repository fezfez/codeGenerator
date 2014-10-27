<?php
namespace CrudGenerator\Tests\General\FileConflict\FileConflictManagerFactory;

use CrudGenerator\FileConflict\FileConflictManagerFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $context = $this->createMock('CrudGenerator\Context\CliContext');

        $this->assertInstanceOf(
            'CrudGenerator\FileConflict\FileConflictManager',
            FileConflictManagerFactory::getInstance($context)
        );
    }
}
