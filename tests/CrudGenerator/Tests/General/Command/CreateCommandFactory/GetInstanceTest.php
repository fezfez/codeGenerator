<?php
namespace CrudGenerator\Tests\General\Command\CreateCommandFactory;

use CrudGenerator\Command\CreateCommandFactory;
use CrudGenerator\Tests\TestCase;

class GetInstanceTest extends TestCase
{
    public function testInstance()
    {
        $application = $this->createMock('Symfony\Component\Console\Application');

        $this->assertInstanceOf(
            'CrudGenerator\Command\CreateCommand',
            CreateCommandFactory::getInstance($application)
        );
    }
}
