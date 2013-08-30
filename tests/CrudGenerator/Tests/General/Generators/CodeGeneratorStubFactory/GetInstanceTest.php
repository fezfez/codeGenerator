<?php
namespace CrudGenerator\Tests\General\Generators\CodeGeneratorStubFactory;

use CrudGenerator\Generators\CodeGeneratorStubFactory;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\DialogHelper;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
                            ->disableOriginalConstructor()
                            ->getMock();

        $codeGenerator = new CodeGeneratorStubFactory();
        $this->assertInstanceOf(
            'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator',
            $codeGenerator->create(
                $stubOutput,
                new DialogHelper(),
                'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator'
            )
        );
    }
}
