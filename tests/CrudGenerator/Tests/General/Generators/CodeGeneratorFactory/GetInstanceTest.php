<?php
namespace CrudGenerator\Tests\General\Generators\CodeGeneratorFactory;

use CrudGenerator\Generators\CodeGeneratorFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator',
            CodeGeneratorFactory::getInstance(
                new ConsoleOutput(),
                new ArgvInput(),
                new DialogHelper(),
                'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
            )
        );
    }
}
